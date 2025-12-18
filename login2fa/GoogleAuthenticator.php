<?php

namespace PHPGangsta;

/**
 * Google Authenticator PHP class - WERKENDE VERSIE
 */
class GoogleAuthenticator
{
    protected $_codeLength = 6;
    
    public function createSecret($secretLength = 16)
    {
        $validChars = $this->_getBase32LookupTable();
        $secret = '';
        
        if (function_exists('random_bytes')) {
            $randomBytes = random_bytes($secretLength);
            for ($i = 0; $i < $secretLength; $i++) {
                $secret .= $validChars[ord($randomBytes[$i]) & 31];
            }
        } else {
            for ($i = 0; $i < $secretLength; $i++) {
                $secret .= $validChars[mt_rand(0, 31)];
            }
        }
        
        return $secret;
    }
    
    public function getQRCodeGoogleUrl($name, $secret, $title = null)
    {
        $urlencoded = urlencode('otpauth://totp/' . $name . '?secret=' . $secret . '&issuer=' . ($title ?: 'TCRHELDEN'));
        return 'https://quickchart.io/qr?text=' . $urlencoded . '&size=200';
    }
    
    // DEZE FUNCTIE IS GEFIXED!
    public function verifyCode($secret, $code, $discrepancy = 1)
    {
        // Zorg dat code een string is
        $code = (string)$code;
        
        $currentTimeSlice = floor(time() / 30);
        
        for ($i = -$discrepancy; $i <= $discrepancy; ++$i) {
            $calculatedCode = $this->getCode($secret, $currentTimeSlice + $i);
            
            // Debug output (verwijder in productie)
            // error_log("Trying: input=$code, calculated=$calculatedCode, slice=" . ($currentTimeSlice + $i));
            
            if ($this->timingSafeEquals($calculatedCode, $code)) {
                return true;
            }
        }
        
        return false;
    }
    
    public function getCode($secret, $timeSlice = null)
    {
        if ($timeSlice === null) {
            $timeSlice = floor(time() / 30);
        }
        
        // Decodeer de base32 secret
        $secretkey = $this->_base32Decode($secret);
        if ($secretkey === false) {
            return false;
        }
        
        // Pack de tijd als binary string
        $time = pack('N*', 0) . pack('N*', $timeSlice);
        
        // Hash het
        $hm = hash_hmac('SHA1', $time, $secretkey, true);
        
        // Gebruik laatste nibble als offset
        $offset = ord(substr($hm, -1)) & 0x0F;
        
        // Neem 4 bytes vanaf de offset
        $hashpart = substr($hm, $offset, 4);
        
        // Unpack naar integer
        $value = unpack('N', $hashpart);
        $value = $value[1];
        
        // Alleen 31 bits
        $value = $value & 0x7FFFFFFF;
        
        // Modulo voor 6 cijfers
        $modulo = pow(10, $this->_codeLength);
        
        return str_pad($value % $modulo, $this->_codeLength, '0', STR_PAD_LEFT);
    }
    
    protected function _base32Decode($secret)
    {
        if (empty($secret)) {
            return '';
        }
        
        $base32chars = $this->_getBase32LookupTable();
        $base32charsFlipped = array_flip($base32chars);
        
        // Verwijder padding
        $secret = str_replace('=', '', $secret);
        $secret = strtoupper($secret); // Zorg voor uppercase
        
        // Controleer of alle karakters geldig zijn
        if (!preg_match('/^[' . preg_quote(implode('', array_slice($base32chars, 0, 32)), '/') . ']+$/', $secret)) {
            return false;
        }
        
        $secret = str_split($secret);
        $binaryString = '';
        
        for ($i = 0; $i < count($secret); $i += 8) {
            $x = '';
            
            for ($j = 0; $j < 8; ++$j) {
                if (isset($secret[$i + $j])) {
                    $x .= str_pad(base_convert($base32charsFlipped[$secret[$i + $j]], 10, 2), 5, '0', STR_PAD_LEFT);
                } else {
                    $x .= '00000';
                }
            }
            
            $eightBits = str_split($x, 8);
            foreach ($eightBits as $byte) {
                $binaryString .= chr(base_convert($byte, 2, 10));
            }
        }
        
        return $binaryString;
    }
    
    protected function _getBase32LookupTable()
    {
        return array(
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
            'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P',
            'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X',
            'Y', 'Z', '2', '3', '4', '5', '6', '7',
            '=',  // padding char
        );
    }
    
    private function timingSafeEquals($safeString, $userString)
    {
        if (function_exists('hash_equals')) {
            return hash_equals($safeString, $userString);
        }
        
        $safeLen = strlen($safeString);
        $userLen = strlen($userString);
        
        if ($userLen != $safeLen) {
            return false;
        }
        
        $result = 0;
        for ($i = 0; $i < $userLen; ++$i) {
            $result |= (ord($safeString[$i]) ^ ord($userString[$i]));
        }
        
        return $result === 0;
    }
}
?>