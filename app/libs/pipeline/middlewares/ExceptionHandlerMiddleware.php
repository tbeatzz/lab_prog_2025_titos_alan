<?php

namespace app\libs\pipeline\middlewares;

use app\libs\database\Connection;
use app\libs\pipeline\middlewares\base\BaseMiddleware;
use app\libs\pipeline\middlewares\base\InterfaceMiddleware;
use app\libs\http\Request;
use app\libs\http\Response;

/**
 * Middleware de manejo de excepciones.
 * 
 * Captura las excepciones que ocurran en el resto de la cadena de middlewares/controladores
 * y devuelve una respuesta adecuada.
 * También realiza rollback de transacciones activas si corresponde.
 * 

 */
final class ExceptionHandlerMiddleware extends BaseMiddleware implements InterfaceMiddleware
{
    /**
     * Constructor por defecto. Llama al constructor base.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Ejecuta el siguiente middleware y captura las excepciones que puedan ocurrir.
     * Si ocurre una excepción:
     * - Hace rollback de la transacción si está activa.
     * - Envía una respuesta de error genérica (para PDO) o con el mensaje real (para otras excepciones).
     *
     * @param Request $request Objeto de la solicitud HTTP.
     * @param Response $response Objeto de la respuesta HTTP.
     */
    public function handler(Request $request, Response $response): void
    {
        try {
            // Ejecuta el siguiente middleware/controlador en la cadena
            $this->handlerNext($request, $response);
        }
        catch (\PDOException $ex) {
            // Si ocurre una excepción de base de datos, se revierte la transacción (si existe)
            $conn = Connection::get();
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }

            // Respuesta genérica por seguridad
            $response->setMessage("");
            $response->setError("Error interno. Consulte con el administrador del sistema.");
            $response->send();
        }
        catch (\Exception $ex) {
            // Si ocurre cualquier otra excepción, también se revierte la transacción (si existe)
            $conn = Connection::get();
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }

            // Devuelve el mensaje real del error (esto podría ocultarse en producción)
            $response->setMessage("");
            $response->setError("{$ex->getMessage()}");
            $response->send();
        }
    }
}
