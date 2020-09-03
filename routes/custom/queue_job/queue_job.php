<?php
use \App\Jobs\ImportCsv;
use \App\Models\Csv;
use \App\Models\TmpCsv;

Route::get('upload', function () {
	return view('queue.upload');
})->name('upload');

Route::post('upload', function() {
	$File = request()->file('file_csv');
	$path = $File->move(public_path('uploads'), implode('.', [md5(time()), $File->getClientOriginalExtension()]));
	$Csv = Csv::create([
		'name' => $File->getClientOriginalName(),
		'path' => '/uploads/' . basename($path),
		'size' => filesize($path)
	]);

	$File = file($path->getPathname()); // file to Array
	$data = array_slice($File, 1); // remove first row: it's the title
	
	$parts = (array_chunk($data, 1000));
    $i = 1;

    foreach($parts as $line) {
        $filename = public_path('uploads/pending/') . date('d-m-y h:i:s') . $i . '.csv';
        file_put_contents($filename, $line);
        $i++;

        $TmpCsv = TmpCsv::create([
			'path' => '/uploads/pending/' . basename($filename),
			'size' => filesize($filename)
		])->csv()->associate($Csv)->save();
		ImportCsv::dispatch($TmpCsv);
    }
	return redirect()->route('upload')->withSuccess('File was uploaded!');
});