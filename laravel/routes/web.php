<?php

require __DIR__.'/auth.php';

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});

/*Route::get('/scanner', function () {
    return view('scanner');
});*/

/*Route::get('/api/book/{isbn}', function (string $isbn) {
    // Sanitize ISBN - only allow digits and X
    $isbn = preg_replace('/[^0-9X]/', '', strtoupper($isbn));

    // Validate ISBN-13 (starts with 978 or 979 + valid mod-10 check)
    $isValid = false;
    if (preg_match('/^(978|979)\d{10}$/', $isbn)) {
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += ((int) $isbn[$i]) * ($i % 2 === 0 ? 1 : 3);
        }
        $check = (10 - ($sum % 10)) % 10;
        $isValid = ($check === (int) $isbn[12]);
    } elseif (preg_match('/^\d{9}[\dX]$/', $isbn)) { // ISBN-10 mod-11 check
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += ((int) $isbn[$i]) * (10 - $i);
        }
        $last = $isbn[9];
        $sum += ($last === 'X') ? 10 : (int) $last;
        $isValid = ($sum % 11 === 0);
    }

    if (!$isValid) {
        return response()->json(['success' => false, 'error' => 'Invalid ISBN checksum']);
    }

    try {
        // Try Open Library API
        $response = Http::timeout(5)->get("https://openlibrary.org/api/books", [
            'bibkeys' => "ISBN:{$isbn}",
            'format' => 'json',
            'jscmd' => 'data',
        ]);

        $data = $response->json();
        $key = "ISBN:{$isbn}";

        if (!empty($data[$key])) {
            $book = $data[$key];

            // Extract authors
            $authors = '';
            if (!empty($book['authors'])) {
                $authors = implode(', ', array_map(fn($a) => $a['name'] ?? '', $book['authors']));
            }

            // Extract publishers
            $publisher = '';
            if (!empty($book['publishers'])) {
                $publisher = $book['publishers'][0]['name'] ?? '';
            }

            // Get cover image
            $cover = null;
            if (!empty($book['cover'])) {
                $cover = $book['cover']['medium'] ?? $book['cover']['small'] ?? null;
            }

            return response()->json([
                'success' => true,
                'book' => [
                    'title' => $book['title'] ?? 'Unknown Title',
                    'authors' => $authors,
                    'publisher' => $publisher,
                    'publish_date' => $book['publish_date'] ?? null,
                    'pages' => $book['number_of_pages'] ?? null,
                    'cover' => $cover,
                    'url' => $book['url'] ?? null,
                ],
            ]);
        }

        return response()->json(['success' => false, 'error' => 'Book not found']);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => 'Lookup failed']);
    }
});*/
