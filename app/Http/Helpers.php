<?php

namespace App\Http;

use Illuminate\Support\Facades\Auth;
use redirect;

class Helpers {

	public function validarAcessoCrudUser()
	{
		if (Auth::User()->id_perfil ==='3' || Auth::User()->id_perfil ==='4')
        {
        	$mensagem = ['mensagem'=>'Este perfil de usuário não tem acesso para esta funcionalidade'];
             return $mensagem;
        }
	}

    public function validarAcessoCreate()
    {
        if ((Auth::User()->id_perfil ==='4'))
        {
        	$mensagem = ['mensagem'=>'Este perfil de usuário não tem acesso para esta funcionalidade'];
             return $mensagem;
        }
    }

    public function validarAcessoEdit($situacao = NULL)
    {

        if ((Auth::User()->id_perfil ==='4' || Auth::User()->id_perfil ==='3' ))
        {

        	if (Auth::User()->id_perfil === '3'){

        		if ($situacao != '3'){

        			$mensagem = ['mensagem'=>'Este perfil de usuário só permite edição se a situação estiver pendente'];
             		return $mensagem;
        		}
        		return false;
        	}

        	$mensagem = ['mensagem'=>'Este perfil de usuário não tem acesso para esta funcionalidade'];
             return $mensagem;
        }
    }

	function system_format($string, $type = NULL)
	{
	    switch ($type)
	    {
	        case 'fone':
	            $result = '(' . substr($string, 0, 2) . ') ' . substr($string, 2, 4) . '-' . substr($string, 6);
	        break;

	        case 'cep':
	            $result = substr($string, 0, 5) . '-' . substr($string, 5, 3);
	        break;

	        case 'cpf':
				while (strlen($string) < 11)
				{
					$string = "0" . $string;
				}

	            $result = substr($string, 0, 3) . '.' . substr($string, 3, 3) . '.' . substr($string, 6, 3) . '-' . substr($string, 9, 2);
	        break;

	        case 'cnpj':
	            $result = substr($string, 0, 2) . '.' . substr($string, 2, 3) . '.' . substr($string, 5, 3) . '/' . substr($string, 8, 4) . '-' . substr($string, 12, 2);
	        break;

	        case 'rg':
	            $result = substr($string, 0, 2) . '.' . substr($string, 2, 3) . '.' . substr($string, 5, 3);
	        break;

	        case 'money_real':
	        	$result = number_format($string, 2, ',', '.');
	        break;

	        case 'horario_funcionamento_inicio':
	        	$result = substr($string, 0, 5);
	        break;

	        case 'horario_funcionamento_fim':
	        	$result = substr($string, 8, 5);
	        break;

	        case 'money_real_db_format':
	        	$result = str_replace(".", "", $string);
	        	$result = str_replace(",", ".", $result );
	        	$result = number_format($result, 2, '.', '');
	        break;


	    }

	    return $result;
	}

	function remove_cnpj($string)
	{
		return str_replace(['.', '-', '/', ','], '', $string);
	}

	function db_format($string, $type)
	{
		switch($type)
		{
			case 'tel':
				$result = substr($string, 1, 2) . substr($string, 4, 4) . substr($string, 9, 5);
			break;

			case 'cel':
				$result = substr($string, 1, 2) . substr($string, 4, 4) . substr($string, 9, 6);
			break;

			case 'cep':
        		$result = substr($string, 0, 5) . substr($string, 6, 3);
        	break;

        	case 'cnpj':
        		$result = substr($string, 0, 2) . substr($string, 2, 3) . substr($string, 5, 3) . substr($string, 8, 4) . substr($string, 12, 2);
        	break;

        	default:
				$result = 'ERROR';
	        break;
		}

		return $result;
	}

	// Altera a data para o formato padrão do MySQL
	function date_db_format($date, $delimiter="/")
	{
		$new_date = "";

		if($date)
		{
	    	$new_date = explode($delimiter, $date);
	    	$new_date = $new_date[2] .'-'. $new_date[1] .'-'. $new_date[0];
		}

	    return $new_date;

	}

	// Altera a data para o formato do sistema
	function date_system_format($date)
	{
		$new_format = explode("-", $date);
		return $new_format[2] .'/'. $new_format[1] .'/'. $new_format[0];
	}

	//Altera o tempo data para o formato do sistema
	function datetime_system_format($datetime)
	{
		$date = substr($datetime, 0, 10); //2015-01-01
		$date = explode('-', $date);

		$new_date = $date[2].'/'.$date[1].'/'.$date[0];
		$new_hour = substr($datetime, 10, 20);

		$new_datetime = $new_date.' '.$new_hour;

		return $new_datetime;
	}

	function date_system_format_oracle($datetime)
	{
		$new_date = "";

		if(!empty($datetime))
		{
			$date = substr($datetime, 0, 10);
			$date = explode('-', $date);
			$new_date = $date[2] . '/' . $date[1] . '/' . $date[0];
		}

		return $new_date;
	}

	function date_system_format_relatorio($date)
	{
		$date = explode('-', $date);
		//BUSCA O NOME DO MÊS ATRAVÉS DO NÚMERO
		$month = $this->nameMonth(substr($date[1], 0, 2));
	
		$new_date = ['dia' => substr($date[2], 0, 2), 'mes' => $month, 'ano' => $date[0]];

		return $new_date;
	}

	function nameMonth($numMonth)
	{			
		$months = array("01" => "Janeiro", "02" => "Fevereiro", "03" => "Março", "04" => "Abril", "05" => "Maio", "06" => "Junho", "07" => "Julho", "08" => "Agosto", "09" => "Setembro", "10" => "Outubro", "11" => "Novembro", "12" => "Dezembro");

		$month = $months[$numMonth];

		return $month;		
	}

	function datetime_db_format($datetime)
	{
		if(!empty($datetime))
		{
			$date = substr($datetime, 0, 10);
			$date = explode('/', $date);
			$new_date = $date[2].'-'.$date[1].'-'.$date[0];
			$new_hour = substr($datetime, 10, 20);

			$new_datetime = $new_date.' '.$new_hour;

			return $new_datetime;
		}
	}

	function isValidaCPF($cpf)
	{
		$cpf = str_pad(preg_replace('/[^0-9_]/', '', $cpf), 11, '0', STR_PAD_LEFT);

		for ($x = 0; $x < 10; $x++)
		{
			if ($cpf == str_repeat($x, 11))
			{
				return false;
			}
		}

		if (strlen($cpf) != 11)
		{
			return false;
		}
		else
		{
			for ($t = 9; $t < 11; $t++)
			{
				for ($d = 0, $c = 0; $c < $t; $c++)
				{
					$d += $cpf{$c} * (($t + 1) - $c);
				}

				$d = ((10 * $d) % 11) % 10;

				if ($cpf{$c} != $d)
				{
					return false;
				}
			}

			return true;
		}
	}

	function retiraCaractereEspecial($string)
	{

		// matriz de entrada
        $what = array( 'ä','ã','à','á','â','ê','ë','è','é','ï','ì','í','ö','õ','ò','ó','ô','ü','ù','ú','û','À','Ã','Á','É','Ê','Í','Ó','Õ','Ú','ñ','Ñ','ç','Ç','+','(',')',',',';',':','|','!','"','#','$','%','&','/','=','?','~','^','>','<','ª','º','*','@','¨','\'' );

        // matriz de saída
        $by   = array( 'a','a','a','a','a','e','e','e','e','i','i','i','o','o','o','o','o','u','u','u','u','A','A','A','E','E','I','O','O','U','n','n','c','C','','','','','','','','','','','','','','','','','','','','','','','','','','');

        $result = str_replace($what, $by, $string);
		return $result;
	}

}


?>
