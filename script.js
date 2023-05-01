$(document).ready(() => {
	
	$('#documentacao').on('click', () => {
		//$('#pagina').load('documentacao.html')

		/*
		$.get('documentacao.html', data => { 
			$('#pagina').html(data)
		})
		*/
		$.post('documentacao.html', data => { 
			$('#pagina').html(data)
		})
	})

	$('#suporte').on('click', () => {
		//$('#pagina').load('suporte.html')

		/*
		$.get('suporte.html', data => { 
			$('#pagina').html(data)
		})
		*/
		$.post('suporte.html', data => { 
			$('#pagina').html(data)
		})
	})

	//ajax
	$('#competencia').on('change', e => {

		let competencia = $(e.target).val()
		
		$.ajax({
			type: 'GET',
			url: 'app.php',
			data: `competencia=${competencia}`, //x-www-form-urlencode
			dataType: 'json',
			success: dados => {
				$('#numeroVendas').html(dados.numeroVendas);
				$('#totalVendas').html(dados.totalVendas);
				$('#totalNumeroVendas').html(dados.totalNumeroVendas);
				$('#totalClientesInativos').html(dados.totalClientesInativos);
				$('#totalReclamacoes').html(dados.totalReclamacoes);
				$('#totalReclamacoes2').html(dados.totalReclamacoes2);
				$('#totalReclamacoes3').html(dados.totalReclamacoes3);
				$('#totalDespesas').html(dados.totalDespesas);
				console.log(dados);

			},
			error: erro => {console.log(erro)},
		})
	})
})