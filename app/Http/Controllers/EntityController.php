<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class EntityController extends Controller
{
    public function extract(Request $request)
    {
        try {
            $url = $request->input('url');

            // Definir la ruta al script en Python
            $scriptPath = base_path('scripts' . DIRECTORY_SEPARATOR . 'extract_entities.py');


            // Definir la ruta al ejecutable de Python
            $pythonPath = 'C:\\Users\\jorge\\AppData\\Local\\Programs\\Python\\Python312\\python.exe';

            // Verificar si las rutas son correctas
            if (!file_exists($scriptPath)) {
                throw new \Exception("El script de Python no se encontró en la ruta: " . $scriptPath);
            }

            if (!file_exists($pythonPath)) {
                throw new \Exception("El ejecutable de Python no se encontró en la ruta: " . $pythonPath);
            }



            // Ejecutar el script de Python con la URL como argumento
            $process = new Process([$pythonPath, $scriptPath, $url], null, null, null, 30);
            $process->run();

            // Capturar la salida y la salida de error
            $output = $process->getOutput();
            $errorOutput = $process->getErrorOutput();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            // Intentar decodificar el JSON devuelto por el script
            $decodedOutput = json_decode($output, true);

            // Verificar si el JSON es válido
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON output from Python script: ' . json_last_error_msg());
            }

            // Devolver la salida como JSON
            return response()->json($decodedOutput);
        } catch (\Exception $e) {
            // Manejar errores no controlados
            return response()->json([
                'error' => 'An unexpected error occurred',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
