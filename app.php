<?php

//classe dashboard
class Dashboard {

	public $data_inicio;
	public $data_fim;
	public $numeroVendas;
	public $totalVendas;

	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
		return $this;
	}
}

//classe de conexão bd
class Conexao {
	private $host = 'localhost';
	private $dbname = 'dashboard';
	private $user = 'root';
	private $pass = '';

	public function conectar() {
		try {

			$conexao = new PDO(
				"mysql:host=$this->host;dbname=$this->dbname",
				"$this->user",
				"$this->pass"
			);

			//
			$conexao->exec('set charset utf8');

			return $conexao;

		} catch (PDOException $e) {
			echo '<p>'.$e->getMessege().'</p>';
		}
	}
}

//classe (model)
class Bd {
	private $conexao;
	private $dashboard;

	public function __construct(Conexao $conexao, Dashboard $dashboard) {
		$this->conexao = $conexao->conectar();
		$this->dashboard = $dashboard;
	}

	public function getNumeroVendas() {
		$query = '
			select 
				count(*) as numero_vendas 
			from 
				tb_vendas 
			where 
				data_venda between :data_inicio and :data_fim';

		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
		$stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;
	}

	public function getTotalVendas() {
		$query = '
			select 
				SUM(total) as total_vendas 
			from 
				tb_vendas 
			where 
				data_venda between :data_inicio and :data_fim';

		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
		$stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_OBJ)->total_vendas;
	}

    public function getTotalClientesAtivos() {
        $query = '
            select 
                count(*) as total_clientes_ativos
            from 
                tb_clientes
            where
                data_venda between :data_inicio and :data_fim and cliente_ativo=1';

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
            $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
            $stmt->execute();

		return $stmt->fetch(PDO::FETCH_OBJ)->total_clientes_ativos;
    }

    public function getTotalClientesInativos() {
        $query = '
            select 
                count(*) as total_clientes_inativos
            from 
                tb_clientes
            where
                data_venda between :data_inicio and :data_fim and cliente_ativo=0';

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
            $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
            $stmt->execute();

		return $stmt->fetch(PDO::FETCH_OBJ)->total_clientes_inativos;
    }

    public function getTotalReclamacoes() {
        $query = '
            select 
                count(*) as total_reclamacoes
            from 
                tb_contatos
            where
                data_venda between :data_inicio and :data_fim and tipo_contato=1';

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
            $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
            $stmt->execute();

		return $stmt->fetch(PDO::FETCH_OBJ)->total_reclamacoes;
    }

	public function getTotalReclamacoes2() {
        $query = '
            select 
                count(*) as total_reclamacoes2
            from 
                tb_contatos
            where
                data_venda between :data_inicio and :data_fim and tipo_contato=2';

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
            $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
            $stmt->execute();

		return $stmt->fetch(PDO::FETCH_OBJ)->total_reclamacoes2;
    }

	public function getTotalReclamacoes3() {
        $query = '
            select 
                count(*) as total_reclamacoes3
            from 
                tb_contatos
            where
                data_venda between :data_inicio and :data_fim and tipo_contato=3';

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
            $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
            $stmt->execute();

		return $stmt->fetch(PDO::FETCH_OBJ)->total_reclamacoes3;
    }


    public function getTotalDespesas() {
		$query = '
			select 
				SUM(total) as total_despesas 
			from 
				tb_despesas 
			where 
				data_despesa between :data_inicio and :data_fim';

		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
		$stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_OBJ)->total_despesas;
	}
    
}


//lógica do script
$dashboard = new Dashboard();

$conexao = new Conexao();

$competencia = explode('-', $_GET['competencia']);
$ano = $competencia[0];
$mes = $competencia[1];

$dias_do_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

$dashboard->__set('data_inicio', $ano.'-'.$mes.'-01');
$dashboard->__set('data_fim', $ano.'-'.$mes.'-'.$dias_do_mes);


$bd = new Bd($conexao, $dashboard);

$dashboard->__set('numeroVendas', $bd->getNumeroVendas());
$dashboard->__set('totalVendas', $bd->getTotalVendas());
$dashboard->__set('totalNumeroVendas', $bd->getTotalClientesAtivos());
$dashboard->__set('totalClientesInativos', $bd->getTotalClientesInativos());
$dashboard->__set('totalReclamacoes', $bd->getTotalReclamacoes());
$dashboard->__set('totalReclamacoes2', $bd->getTotalReclamacoes2());
$dashboard->__set('totalReclamacoes3', $bd->getTotalReclamacoes3());
$dashboard->__set('totalDespesas', $bd->getTotalDespesas());

echo json_encode($dashboard);
//print_r($ano.'/'.$mes.'/'.$dias_do_mes);



?>