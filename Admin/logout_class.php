<?php
class logout{

    function deleteCookie($cookieName){
        // Deletes and unsets a cookie
        setcookie($cookieName, "", time() - 1, "/");
        unset($_COOKIE[$cookieName]);
    }

    private function logError($error){
        $logDirectory = __DIR__ . '/log';
        $logFilePath = $logDirectory . '/database_error_log.txt';
    
        if (!is_dir($logDirectory)){
            mkdir($logDirectory, 0755, true);
        }
        if (!file_exists($logFilePath)){
            touch($logFilePath);
        }
    
        $timestamp = date("Y-m-d H:i:s");
        if($error instanceof Exception){
            $errorMessage = "Error: " . $error->getMessage();
        }
        else{
            $errorMessage = "Error: " . $error;
        }
        $logMessage = "[" . $timestamp . "]" . " - " . $errorMessage . "\n";
      
        file_put_contents($logFilePath, $logMessage, FILE_APPEND);
    }

    
public function deleteSessionToken($input_sessionToken){
    include "connection.php";
        $query = "
        DELETE FROM LoginToken
        WHERE session_token = ?
        ";

        try{
            $stmt = $this->$conn->prepare($query); $stmt->bind_param("s", $input_sessionToken);
            $stmt->execute(); $stmt->close();
        }
        catch(Exception $e){
            $this->logError($e);
        }
    }
}
    ?>