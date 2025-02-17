<?php

namespace App\Command;

use App\Entity\ProductData;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use League\Csv\Statement;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import-csv',
    description: 'Imports product data from a CSV file into the database.',
)]
class ImportCsvCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::REQUIRED, 'Path to the CSV file')
            ->addArgument('mode', InputArgument::OPTIONAL, 'Mode: "test" or "live"', 'live');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = $input->getArgument('file');
        $mode = $input->getArgument('mode')??'live';

        if (!file_exists($filePath)) {
            $output->writeln("<error>File not found: $filePath</error>");
            return Command::FAILURE;
        }

        $output->writeln("<info>Starting CSV import in '$mode' mode...</info>");

        // Read the CSV file
        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0); // Assumes the first row is the header

        $stmt = Statement::create();
        $records = $stmt->process($csv);

        $processed = 0;
        $successful = 0;
        $skipped = 0;
        $errors = [];
        $dateNow = new \DateTime();
        foreach ($records as $record) {
            $processed++;

            // Extract fields from CSV (assuming correct column names)
            $productName = $record['Product Name'] ?? null;
            $productDesc = $record['Product Description'] ?? null;
            $productCode = $record['Product Code'] ?? null;
            $price = floatval($record['Cost in GBP'] ?? 0);
            $stock = intval($record['Stock'] ?? 0);
            $discontinued = filter_var($record['Discontinued'] ?? false, FILTER_VALIDATE_BOOLEAN);

            // Apply import rules
            if (($price < 5 && $stock < 10) || $price > 1000) {
                $output->writeln("<comment>Skipping row $processed: Does not meet import criteria (Price: $price, Stock: $stock)</comment>");
                $skipped++;
                continue;
            }
            // Check if the product code already exists in the database
            $existingProduct = $this->entityManager->getRepository(ProductData::class)
                ->findOneBy(['ProductCode' => $productCode]);

            if ($existingProduct) {
                $output->writeln("<comment>Skipping row $processed: Already Existed (ProductCode: $productCode)</comment>");
                $skipped++;
                continue;
            }
            // Create product entity
            $product = new ProductData();
            $product->setProductName($productName);
            $product->setProductDesc($productDesc);
            $product->setProductCode($productCode);
            $product->setStock($stock);
            $product->setPrice($price);
            $product->setAdded($dateNow);

            // Handle discontinued products
            if ($discontinued) {
                $product->setDiscontinued($dateNow); // Set to current date
            }

            // Insert into DB unless in test mode
            try {
                if ($mode === 'live') {
                    $this->entityManager->persist($product);
                    $this->entityManager->flush();
                }
                $successful++;
            } catch (\Exception $e) {
                $errors[] = "Row $processed: " . $e->getMessage();
                $skipped++;
            }
        }

        // Summary report
        $output->writeln("<info>Import completed.</info>");
        $output->writeln("<info>Total processed: $processed</info>");
        $output->writeln("<info>Successful imports: $successful</info>");
        $output->writeln("<info>Skipped rows: $skipped</info>");

        // Print errors if any
        if (!empty($errors)) {
            $output->writeln("<error>Errors encountered:</error>");
            foreach ($errors as $error) {
                $output->writeln("<error>$error</error>");
            }
        }

        return Command::SUCCESS;
    }
}
