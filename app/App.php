<?php

declare(strict_types = 1);

// Your Code
function getTransactionFiles(string $dirpath): array
{
    $files = [];
    foreach (scandir($dirpath) as $file){
       if (is_dir($file)){
           continue;
       }
       $files[] =  $dirpath . $file;
    }
    return $files;
}

function getTransactions(string $filename, ?callable $transactionHandler = null): array
{
    if (! file_exists($filename)){
       trigger_error('File "' .  $filename . '"doest not extist.', E_USER_ERROR);
    }

    $file = fopen($filename , 'r');
    fgetcsv($file);
    $transactions = [];

   while    (($transaction = fgetcsv($file)) != false){
       if ($transactionHandler != null){
           $transaction = $transactionHandler($transaction);
       }

       $transactions[] = $transaction;
   }

return $transactions;
}

function extractTransaction(array $TransactionRow): array
{
    [$date, $checknumber, $description, $amount] = $TransactionRow;
$amount =  (float) str_replace(['$', ','], '', $amount);

return [
    'date' => $date,
    'checknumber' => $checknumber,
    'description' => $description,
    'amount' => $amount,
    ];
}




function calculatetotals(array $transactions): array
{
  $totals = ['nettotal' => 0, 'totalIncome' => 0, 'totalExpense' =>0 ];

  foreach ($transactions as $transaction){
      $totals['nettotal'] += $transaction['amount'];

    if ($transaction['amount'] >= 0){
        $totals['totalIncome'] += $transaction['amount'];
    } else{
      $totals['totalExpense'] += $transaction['amount'];
    }
  }
  return $totals;
}

