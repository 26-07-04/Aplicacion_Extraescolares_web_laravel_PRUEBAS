<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactMessage;

class ContactController extends Controller
{
    /**
     * Handle contact form submission and send email.
     */
    public function send(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        try {
            Mail::to('soporte.plataforma.itve@gmail.com')
                ->send(new ContactMessage($data));
        } catch (\Exception $e) {
            Log::error('Contact form send error: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withInput()->with('error', 'Error al enviar el mensaje. Intenta de nuevo.');
        }

        return redirect()->route('contact')->with('success', 'Mensaje enviado correctamente. Gracias.');
    }
}
