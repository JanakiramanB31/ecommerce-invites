<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class CsvController extends Controller
{
    public function import(Request $request)
    {
        $file = $request->file('csv_file');

        if (($handle = fopen($file, 'r')) !== false) {
            $header = fgetcsv($handle); // read first row as header

            while (($row = fgetcsv($handle)) !== false) {
                // Assume CSV columns are in order: name, email, age
                User::create([
                    'name'  => $row[0],
                    'email' => $row[1],
                    'age'   => $row[2],
                ]);
            }

            fclose($handle);
        }

        return 'Import done!';
    }
}


<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class CsvController extends Controller
{
    public function importCsv(Request $request)
    {
        // Validate the file
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $path = $request->file('csv_file')->getRealPath();
        $data = [];

        if (($handle = fopen($path, 'r')) !== false) {
            $header = null;

            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $rowData = array_combine($header, $row);

                    // Insert into database
                    User::create([
                        'name'  => $rowData['name'],
                        'email' => $rowData['email'],
                        'age'   => $rowData['age'],
                    ]);
                }
            }

            fclose($handle);
        }

        return response()->json(['message' => 'CSV Imported Successfully']);
    }
}


use Illuminate\Http\Request;
use App\Product;

public function import(Request $request)
{
    $file = $request->file('file');
    $fileContents = file($file->getPathname());

    foreach ($fileContents as $line) {
        $data = str_getcsv($line);

        Product::create([
            'name' => $data[0],
            'price' => $data[1],
            // Add more fields as needed
        ]);
    }

    return redirect()->back()->with('success', 'CSV file imported successfully.');
}
public function handle() { 

  $filename = 'path/to/your/file.csv'; 
  if (!file_exists($filename)) { 
      $this->error('CSV file not found!'); return; 
  } 
  $file = fopen($filename, 'r'); 
  $header = fgetcsv($file); 
  // Read the header row 
  while (($row = fgetcsv($file)) !== false) {
     // Process each row and import the record 
    // Example: create a new model instance and save it 
    YourModel::create(array_combine($header, $row)); 
  } 
  fclose($file); 
  $this->info('Import completed successfully!'); 
}     