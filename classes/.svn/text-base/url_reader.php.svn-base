<?php
/**
 * Classe para leitura do conteúdo de um URL usando CURL quando suportado
 * @author Ricardo Martins <ricardo.martins@lbslocal.com>
 *
 *	Como usar:
 *	$reader = new Url_Reader('http://www.uol.com.br/');
 *	if($reader->success()){
 *		echo $reader->get();
 *		echo $reader->get_return_info();
 *	}else{
 *		echo $reader->get_errors();
 *		//var_dump $reader->errors;
 *	}
 *
 *  
 */
class Url_Reader
{
	#config - sera sobrescrito se for passado via constantes
	private $connect_timeout = 10; //CURLOPT_CONNECTTIMEOUT
	private $dns_cache_timeout = 5; //CURLOPT_DNS_CACHE_TIMEOUT
	private $curl_timeout = 15; //CURLOPT_TIMEOUT
	#end config

	public $options = array();
	public $errors = array();

	protected $ch;
	protected $use_curl = FALSE; //vai mudar sozinho, conforme o suporte
	protected $return;
	protected $return_info;

	/**
	 * Cria um novo leitor e faz a leitura usando curl (se disponivel) ou file_get_contents
	 * @param string $url
	 * @param array $options Array de constantes do CURL. 
	 * @see http://br2.php.net/manual/en/function.curl-setopt.php
	 */
	public function __construct($url, array $options = NULL)
	{
		$this->use_curl = (function_exists('curl_init'));

		if($this->use_curl)
		{
			$this->ch = curl_init($url);

			if(!empty($options))
			{
				$this->set_options($options);
			}else{
				$this->options[CURLOPT_CONNECTTIMEOUT] = $this->connect_timeout;
				$this->options[CURLOPT_DNS_CACHE_TIMEOUT] = $this->dns_cache_timeout;
				$this->options[CURLOPT_TIMEOUT] = $this->curl_timeout;
			}
			$this->options[CURLOPT_RETURNTRANSFER] = 1;
			$this->set_options($this->options);

			$this->exec_curl();
		}else{
			$this->exec_file_get_contents($url);
		}
	}

	public function __destruct()
	{
		if($this->use_curl && $this->ch !== NULL)
		@curl_close($this->ch);
	}

	/**
	 * Retorna o resultado da leitura do url
	 */
	public function get()
	{
		return $this->return;
	}
	
	/**
	 * Retorna um array com informações da solicitação (disponivel apenas para curl)
	 * @see http://br2.php.net/manual/en/function.curl-getinfo.php
	 */
	public function get_return_info()
	{
		return $this->return_info;
	}

	public function success()
	{
		return (empty($this->errors));
	}

	/**
	 * Retorna uma string simples com os erros de leitura.
	 * Para obter na forma de array, use get_return_info()
	 */
	public function get_errors()
	{
		return implode('//', $this->errors);
	}


	protected function set_options(array $options)
	{
		$set_options_ok = curl_setopt_array($this->ch,$options);
		if($set_options_ok === FALSE)
		{
			$this->errors[] = _('Falha ao setar uma das opções do CURL.');
		}else{
			foreach($options as $option => $value)
			{
				$this->options[$option] = $value;
			}
		}
	}

	protected function exec_curl()
	{
		$this->return = curl_exec($this->ch);

		if(curl_errno($this->ch) !== 0)
		{
			$this->errors[] = curl_error($this->ch);
		}else{
			$this->return_info = curl_getinfo($this->ch);
		}

		//se o retorno http começa com 2 é pq teve sucesso na requisição
		if(substr(@$this->return_info['http_code'],0,1) != 2)
		{
			if(empty($this->return_info['http_code']))
			{
				$this->errors[] = _('Nenhum codigo HTTP foi retornado.');
			}else{
				$this->errors[] = sprintf(_('O servidor respondeu com codigo %s.'),$this->return_info['http_code']);
			}
		}

		return $this->return;
	}

	protected function exec_file_get_contents($url)
	{
		if(ini_get('allow_url_fopen'))
		{
			try
			{
				$this->return = file_get_contents($this->url);
			}catch (Exception $e){
				$this->errors[] = $e->getMessage();
			}
		}else{
			$this->errors[] = _('Acesso a URLs externas esta desabilitado. Verifique a configuracao do php.ini e veja se a opcao allow_url_fopen esta ativada. Se preferir, habilite o suporte a CURL.');
		}
	}
}