<?php

namespace App\Http\Controllers;

use App\Mail\TxtExport;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpWord\PhpWord;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ActivityExportController extends Controller
{
    public function exportMail(Request $request)
    {
        Mail::to($request->get('email'))->send(new TxtExport($request->get('txt'), $request->get('comment')));

        return \response(json_encode(['status' => 'success']));
    }

    public function exportActivitiesToWord(Request $request)
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $text = htmlspecialchars($request->get('exportText'));
        $section->addText($text);

        $fileName = md5(time());
        $filePath = storage_path('app/word-exports/').$fileName.'.docx';
        try {
            $phpWord->save($filePath);
        } catch (\Exception $e) {
            die($e->getMessage());
        }

        return response()->json(['download' => route('docx-export-download', ['fileName' => $fileName])]);
    }

    public function downloadWordExport($fileName)
    {
        $validator = Validator::make(['filename' => $fileName], ['filename' => 'required|size:32|alpha_num']);

        if ($validator->fails()) {
            throw new \Exception('Unknown export ');
        }

        $filePath = storage_path("app/word-exports/{$fileName}.docx");

        if (!file_exists($filePath)) {
            throw new \Exception('Unknown export');
        }

        $filePath = storage_path("app/word-exports/{$fileName}.docx");

        return response()->download($filePath, 'word-export.docx')->deleteFileAfterSend(true);
    }
}
