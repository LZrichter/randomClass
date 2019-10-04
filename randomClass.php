<?php
/**
 * @author Luiz A Richter <luizantoniorichter@hotmail.com>
 * 
 * Classe que cria valores aleatórios
 * Criando um objeto da classe e chamando uma variável do valor que você deseja;
 * $obj->string, $obj->number, $obj->cellphone
 * 
 * Em 'string' e 'number', você pode adicionar um tamanho no final com _##
 * $obj->string_64, $obj->number_64 ==> Trará uma string aleatória de 64 chars ou números aleatórios de 64 chars
 * 
 * @example
 * $obj = new randomClass;
 * 
 * echo $obj->string           ==> pFDJiwUKbSIKQRRJMIxoRZZXaEyXFedT (String de 32 caractéres, podendo incluir espaço em branco)
 * echo $obj->number           ==> 4932347263145189 (Número de 16 caractéres)
 * echo $obj->cellphone        ==> (47) 80213-4237
 * echo $obj->email            ==> aAdvjVAD_ihQpzMU@uxcprfmowc.co.kp
 * 
 * echo $obj->string_64        ==> HKidCGMficbjKzwMqZxWnPJEXQDICRlREJvpMOoAySUvPSbqcDgbAXtSGAGggVoy (String de 64 caractéres)
 * echo $obj->number_8         ==> 49086460 (Número de 8 caractéres)
 * 
 * // Lembrando: string_## ou number_## é qualquer tamanho desejado
 */
class randomClass{
    
    function __get($varName){
        $opt = null;

        if(strpos($varName, "_")){
            $exp = explode("_", $varName);

            $typeSel = $exp[0];
            $opt = $exp[1];
        }else{
            $typeSel = $varName;         
        }

        switch ($typeSel){
            case 'string':
                return $this->randomString($opt);
            break;
            case 'number':
                return $this->randomNumber($opt);
            break;
            case 'cellphone':
                return $this->randomCellphone();
            break;
            case 'email':
                return $this->randomEmail();
            break;
            case 'cpf':
                return $this->randomCPF(($opt ? true : false));
            break;
            case 'cnpj':
                return $this->randomCNPJ(($opt ? true : false));
            break;
            case 'date':
                return $this->randomDate(($opt ? true : false));
            break;
            case 'gender':
                return $this->randomGender();
            break;
            case 'rg':
                return $this->randomRG();
            break;
            
            default:
                return $this->randomNumber($opt);
            break;
        }
    }

    /**
     * Cria um string aleatória, com tamanho padrão de 32 caractéres
     * @example
     *     $obj = new randomClass;
     *     echo $obj->string;    ==> KWZXJjvZecNwobfBdddZSGLauJIrfLZj
     *     // $obj->string_##    -> '##' Podendo ser qualquer tamanho
     *     echo $obj->string_64; ==> QJnnSbGPcOHyPswDUMCNJvgnbrMRpYyIpoXytNkytsVuICfycpvuVdVucSjjTer
     * @param  integer $size Tamanho da string, com padrão null
     * @return string       String aleatória
     */
    private function randomString($size = null){
        // Caractéres que estarão presentes na string
        $keyspace = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ ';
        
        if(isset($size) && !empty($size))
            $length = $size;
        else $length = 32;

        return $this->randomCharset($keyspace, $length);
    }

    /**
     * Cria um número aleatório. Tamanho padrão é de 16 caractéres
     * Retorna uma string com os números para não estorar o limite de int do php
     * @example
     *     $obj = new randomClass;
     *     echo $obj->number;   ==> '3145090969335146'
     *     // $obj->number_##   -> '##' Podendo ser qualquer tamanho
     *     echo $obj->number_8; ==> '01206988'
     * 
     *     $number  = $this->randomNumber();
     *     $number8 = $this->randomNumber(8);
     *     echo $number;  ==> 1795231008519612
     *     echo $number8; ==> 57684603
     * @param  integer $size Tamanho máximo de caractéres no número
     * @return string        String com o número
     */
    private function randomNumber($size = null){
        // Caractéres que estarão presentes no número
        $keyspace = '0123456789';
        
        if(isset($size) && !empty($size))
            $length = $size;
        else $length = 16;

        return $this->randomCharset($keyspace, $length);
    }

    /**
     * Cria um numero de telefone celular no padrão brasileiro (##) #####-####
     * @example
     *     $obj = new randomClass;
     *     echo $obj->cellphone; ==> '(84) 22283-8859'
     * 
     *     $cell = new randomCellphone();
     *     echo $cell; ==> (51) 29470-5231
     * @return string String com o telefone celular
     */
    private function randomCellphone(){
        $keyspace = '0123456789';

        $DDDCode = $this->randomDDD();
        $firstNumber = $this->randomCharset($keyspace, 5, true);
        $secndNumber = $this->randomCharset($keyspace, 4, true);

        return "(" . $DDDCode . ") " . $firstNumber . "-" . $secndNumber;
    }

    /**
     * Cria um email aleatório com TLDs válidos (16 chars antes de @ e 10 chars depois)
     * @example
     *     $obj = new randomClass;
     *     echo $obj->email; ==> 'cPDg_-MDtQCqza_Z@zamqdqcxrf.cv'
     * 
     *     $email = $this->randomEmail();
     *     echo $email; ==> uAESByihDUCDFWCb@ghmaogfnfn.bj
     * @return string Email aleatório
     */
    private function randomEmail(){
        $keyspaceFront = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-';
        $keyspaceBack = 'abcdefghijklmnopqrstuvwxyz';

        $first = $this->randomCharset($keyspaceFront, 16);
        $secnd = $this->randomCharset($keyspaceBack, 10);
        $rdTLD = $this->randomTLD();

        return $first . "@" . $secnd . "." . strtolower($rdTLD);
    }

    /**
     * Cria uma data aleatório no limite do sql (Entre 1000-01-01 a 9999-01-01)
     * @example
     *     $obj = new randomClass;
     *     echo $obj->date; ==> '23/05/2733'
     *     echo $obj->date_sql; ==> '4611-03-31'
     * 
     *     $date = $this->randomDate();
     *     $sqldate = $this->randomDate(true);
     *     echo $date; ==> '09/11/1516'
     *     echo $sqldate; ==> '4782-07-24'
     * @return string Data aleatória
     */
    private function randomDate($sqlDate = false){
        $year = rand(1000, 9999);
        $month = rand(1, 12);
        $month = strlen($month) < 2 ? "0" . $month : $month;

        $numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $day = rand(1, $numDays);
        $day = strlen($day) < 2 ? "0" . $day : $day;

        if($sqlDate) return $year . "-" . $month . "-" . $day;
        return $day . "/" . $month . "/" . $year;
    }

    /**
     * Cria um CPF aleatório válido
     * @example
     *     $obj = new randomClass;
     *     echo $obj->cpf;      ==> '53922777058'
     *     echo $obj->cpf_mask; ==> '743.484.278-44'
     * @param  boolean $mask TRUE para máscara no CPF
     * @return string        CPF aleatório (Com máscara ou não)
     */
    private function randomCPF($mask = false){
        $num = array();

        for($i = 1; $i <= 9; $i++)
            $num[$i] = rand(0, 9);
        
        $sum1 = $sum2 = 0; $count = 2;
        for($i = 9; $i >= 1; $i--){
            $sum1 += $num[$i] * $count;
            $sum2 += $num[$i] * ($count + 1);

            $count++;
        }

        $digit1 = 11 - ($sum1 % 11);
        $digit1 = ($digit1 >= 10) ? 0 : $digit1;
        
        $digit2 = 11 - (($digit1 * 2 + $sum2) % 11);
        $digit2 = ($digit2 >= 10) ? 0 : $digit2;

        if($mask)
            return $num[1] . $num[2] . $num[3] . "." . $num[4] . $num[5] . $num[6] . "." . $num[7] . $num[8] . $num[9] . "-" . $digit1 . $digit2;
        else return implode("", $num) . $digit1 . $digit2;
    }

    /**
     * Cria um RG aleatório
     * @example
     *     $obj = new randomClass;
     *     echo $obj->rg; ==> '6433191542'
     *     
     *     $rg = $this->randomRG();
     *     echo $rg; ==> '8035832548'
     * @return string RG aleatório
     */
    private function randomRG(){
        for($i = 1; $i <= 10; $i++)
            $num[$i] = rand(0, 9);
        
        return implode("", $num);
    }

    /**
     * Cria um CNPJ aleatório válido
     * @example 
     *     $obj = new randomClass;
     *     echo $obj->cnpj;      ==> '89699631000154'
     *     echo $obj->cnpj_mask; ==> '88.679.675/0001-50'
     * @param  boolean $mask TRUE para máscara no CNPJ
     * @return string        CNPJ aleatório (Com máscara ou não)
     */
    private function randomCNPJ($mask = false){
        $num = array();

        for($i = 1; $i <= 8; $i++)
            $num[$i] = rand(0, 9);

        $num[9] = 0;
        $num[10] = 0;
        $num[11] = 0;
        $num[12] = 1;

        $sum1 = $sum2 = 0; $count = 2; $count2 = 3;
        for($i = 12; $i >= 1; $i--){
            if($i == 5) $count2 = 2;
            if($i == 4) $count = 2;

            $sum1 += $num[$i] * $count;
            $sum2 += $num[$i] * $count2;

            $count++;
            $count2++;
        }

        $digit1 = 11 - ($sum1 % 11);
        $digit1 = ($digit1 >= 10) ? 0 : $digit1;

        $digit2 = 11 - (($digit1 * 2 + $sum2) % 11);
        $digit2 = ($digit2 >= 10) ? 0 : $digit2;

        if($mask)
            return $num[1] . $num[2] . "." . $num[3] . $num[4] . $num[5] . "." . $num[6] . $num[7] . $num[8] . "/" . $num[9] . $num[10] . $num[11] . $num[12] . "-" . $digit1 . $digit2;
        else return implode("", $num) . $digit1 . $digit2;
    }

    private function randomGender(){
        return (rand(0, 1) ? "M" : "F");
    }

    /**
     * Recebe uma lista de caractéres, um tamanho, e cria uma string aleatória destes caractéres, no tamanho especificado
     * @example
     *     $rand = $this->randomCharset('abcde', 3);
     *     echo $rand; ==> 'bde'
     * @param  string  $keyspace        Lista de caractéres a serem randomizados
     * @param  integer  $length         Tamanho da string
     * @param  boolean $removeZeroFront TRUE para remover o char '0' na frente da string, substituindo para outro char da lista
     * @return string                   Lista aleatória
     */
    private function randomCharset($keyspace, $length, $removeZeroFront = false){
        $pieces = array();
        $max = mb_strlen($keyspace, '8bit') - 1;

        for($i = 0; $i < $length; ++$i)
            $pieces[] = $keyspace[rand(0, $max)];

        if($removeZeroFront && ($pieces[0] === 0 || $pieces[0] === '0')){
            while($pieces[0] === 0 || $pieces[0] === '0')
                $pieces[0] = $keyspace[rand(0, $max)];
        }

        $randomString = implode('', $pieces);

        return $randomString;
    }

    /**
     * Retorna um DDD (Discagem Direta a Distância) aleatório de uma lista de DDDs válidos
     * @example
     *     $ddd = $this->randomDDD();
     *     echo $ddd; ==> '47'
     * @return string DDD aleatório
     */
    private function randomDDD(){
        $DDDs = array('11', '12', '13', '14', '15', '16', '17', '18', '19', '21', '22', '24', '27', '28', '31', '32', '33', '34', '35', '37', '38', '41', '42', '43', '44', '45', '46', '47', '48', '49', '51', '53', '54', '55', '61', '62', '63', '64', '65', '66', '67', '68', '69', '71', '73', '74', '75', '77', '79', '81', '82', '83', '84', '85', '86', '87', '88', '89', '91', '92', '93', '94', '95', '96', '97', '98', '99');

        return $DDDs[rand(0, count($DDDs) - 1)];
    }

    /**
     * Retorna um TLD (Top Level Domain) aleatório, de uma lista de TLDs válidos
     * @example
     *     $tld = $this->randomTLD();
     *     echo $tld; ==> 'MG'
     * @return string TLD aleatório
     */
    private function randomTLD(){
        $TLDs = array('AC', 'AD', 'AE', 'AERO', 'AF', 'AG', 'AI', 'AL', 'AM', 'AN', 'AO', 'AQ', 'AR', 'ARPA', 'AS', 'ASIA', 'AT', 'AU', 'AW', 'AX', 'AZ', 'BA', 'BB', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BIZ', 'BJ', 'BM', 'BN', 'BO', 'BR', 'BS', 'BT', 'BV', 'BW', 'BY', 'BZ', 'CA', 'CAT', 'CC', 'CD', 'CF', 'CG', 'CH', 'CI', 'CK', 'CL', 'CM', 'CN', 'CO', 'COM', 'COOP', 'CR', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ', 'DE', 'DJ', 'DK', 'DM', 'DO', 'DZ', 'EC', 'EDU', 'EE', 'EG', 'ER', 'ES', 'ET', 'EU', 'FI', 'FJ', 'FK', 'FM', 'FO', 'FR', 'GA', 'GB', 'GD', 'GE', 'GF', 'GG', 'GH', 'GI', 'GL', 'GM', 'GN', 'GOV', 'GP', 'GQ', 'GR', 'GS', 'GT', 'GU', 'GW', 'GY', 'HK', 'HM', 'HN', 'HR', 'HT', 'HU', 'ID', 'IE', 'IL', 'IM', 'IN', 'INFO', 'INT', 'IO', 'IQ', 'IR', 'IS', 'IT', 'JE', 'JM', 'JO', 'JOBS', 'JP', 'KE', 'KG', 'KH', 'KI', 'KM', 'KN', 'KP', 'KR', 'KW', 'KY', 'KZ', 'LA', 'LB', 'LC', 'LI', 'LK', 'LR', 'LS', 'LT', 'LU', 'LV', 'LY', 'MA', 'MC', 'MD', 'ME', 'MG', 'MH', 'MIL', 'MK', 'ML', 'MM', 'MN', 'MO', 'MOBI', 'MP', 'MQ', 'MR', 'MS', 'MT', 'MU', 'MUSEUM', 'MV', 'MW', 'MX', 'MY', 'MZ', 'NA', 'NAME', 'NC', 'NE', 'NET', 'NF', 'NG', 'NI', 'NL', 'NO', 'NP', 'NR', 'NU', 'NZ', 'OM', 'ORG', 'PA', 'PE', 'PF', 'PG', 'PH', 'PK', 'PL', 'PM', 'PN', 'POST', 'PR', 'PRO', 'PS', 'PT', 'PW', 'PY', 'QA', 'RE', 'RO', 'RS', 'RU', 'RW', 'SA', 'SB', 'SC', 'SD', 'SE', 'SG', 'SH', 'SI', 'SJ', 'SK', 'SL', 'SM', 'SN', 'SO', 'SR', 'ST', 'SU', 'SV', 'SX', 'SY', 'SZ', 'TC', 'TD', 'TEL', 'TF', 'TG', 'TH', 'TJ', 'TK', 'TL', 'TM', 'TN', 'TO', 'TP', 'TR', 'TRAVEL', 'TT', 'TV', 'TW', 'TZ', 'UA', 'UG', 'UK', 'US', 'UY', 'UZ', 'VA', 'VC', 'VE', 'VG', 'VI', 'VN', 'VU', 'WF', 'WS', 'XXX', 'YE', 'YT', 'ZA', 'ZM', 'ZW');

        return $TLDs[rand(0, count($TLDs) - 1)];
    }

}

// classTest();
// 
function classTest(){
    $obj = new randomClass;

    echo "<b>String:</b> <br>";
    echo $obj->string;

    echo "<br><br> <b>String 64:</b> <br>";
    echo $obj->string_64;

    echo "<br><br> <b>Number:</b> <br>";
    echo $obj->number;

    echo "<br><br> <b>Number 8:</b> <br>";
    echo $obj->number_8;

    echo "<br><br> <b>Cellphone:</b> <br>";
    echo $obj->cellphone;

    echo "<br><br> <b>Email:</b> <br>";
    echo $obj->email;

    echo "<br><br> <b>CPF:</b> <br>";
    echo $obj->cpf;

    echo "<br><br> <b>CPF Mask:</b> <br>";
    echo $obj->cpf_mask;

    echo "<br><br> <b>CNPJ:</b> <br>";
    echo $obj->cnpj;

    echo "<br><br> <b>CNPJ Mask:</b> <br>";
    echo $obj->cnpj_mask;

    echo "<br><br> <b>Gender:</b> <br>";
    echo $obj->gender;

    echo "<br><br> <b>Date:</b> <br>";
    echo $obj->date;

    echo "<br><br> <b>SQL Date:</b> <br>";
    echo $obj->date_sql;

    echo "<br><br> <b>RG:</b> <br>";
    echo $obj->rg;
}