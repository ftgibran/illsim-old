<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"> 
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Illness Simulator - Relatório</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Source+Code+Pro" rel="stylesheet">

<style>
	body {
		font-family: 'Open Sans', sans-serif;	
	}
	.code {
		font-family: 'Source Code Pro', monospace;
	}
	p {
		text-align: justify;
	}
	h2 {
		color: #2a3744;
		text-align: center;
	}
	h2:after {
		content:'';
		display:block;
		margin: 1em 45%;
		border-bottom:4px solid #2a3744;	
	}
	h3 {
		color: #2a3744;
		font-size: 140%;
		margin: 1em auto;
	}
	h3:before {
		content:'';
		display:block;
		margin: 1em 0;
		border-bottom:2px solid #999;	
	}

	table .header {
    	background: whitesmoke;
	}

	table th, table td {
		text-align: center;
	}

	table tr:nth-child(even) {
    	background: whitesmoke;
	}

	table td[rowspan="2"] {
		text-align: left;
		vertical-align: middle !important;
	    background: whitesmoke;
	}

	table dl {
		margin-bottom: 0;
	}

	table dt {
		width: 80px !important;
	}

	table dd {
		margin-left: 100px !important;
	}
</style>

</head>
<body>

<div class="container">
	<h1 class="text-center">Illness Simulator - Relatório</h1><hr>

	<p>
		O Illsim foi elaborado com o objetivo de estudar o comportamento de doenças em diferentes cenários. Para um estudo específico, será atribuído um modelo padrão para servir como referência para as diversas simulações que serão realizadas nesse relatório.
	</p>
	<p>
		O objetivo do estudo é analisar diferentes estratégias de vacinação, nas quais será explorado o método distribuíção (aleatório, maior grau, vizinhos de maior grau), a taxa de distribuição, o número máximo de distribuição e o período da doença que ocorrerá a distribuição. Estas estratégias tem como finalidade extinguir uma pandemia, distribuindo o mínimo de vacinas possíveis na população. 
	</p>

	<h2>Parte 1 - Adotando um modelo padrão</h2>

	<p>
		A simulação irá gerar um cenário de 100 redes constituídas por 100 nós cada ligados em, totalizando 10000 nós ligados entre sí por 40000 arestas. Cinco nós de cada rede são ligados com outros cincos nós de todas as demais redes, ou seja, todas as redes estarão inter-conectadas. O cenário é uma representação de comunidades, sendo que as mesmas estabelecem alguns contatos entre elas. 
	</p>

	<p>
		O número de infectados inicial será de 1%, ou seja, 100 contaminados espalhados nas redes. O restante dos nós (99%) serão estabelecidos como suscetíveis.
	</p>

	<p>
		Cada nó suscetível, a cada passo de tempo, sofrerá uma tentativa de infecção dada pela fórmula: <code class="code">p(S->I) = 1 - exp(-k*n)</code>, sendo <code class="code">k</code> uma constante e <code class="code">n</code> o número de vizinhos infectados dos respectivos nós. Se, por exemplo, <code class="code">k = 0.25</code> e um nó possuir 3 vizinhos infectados, as chances desse nó ser infectado a cada passo de tempo é de <code class="code">p(S->I) = 1 - exp(-0.25*3) = 52.76%</code>.
	</p>

	<p>Outros atributos: </p>

	<ul>
		<li>Cada passo de tempo representa 1 dia</li>
		<li>A constante <code class="code">k</code> no modelo padrão será <code class="code">k = 1</code></li>
		<li>A probabilidade de um infectado se tornar recuperado a cada passo de tempo é de <code class="code">p(I->R) = 60%</code></li>
		<li>A probabilidade de um infectado se tornar suscetível a cada passo de tempo é de <code class="code">p(I->S) = 30%</code></li>
		<li>A probabilidade de um recuperado se tornar suscetível a cada passo de tempo é de <code class="code">p(R->S) = 10%</code></li>
	</ul>

	<p>
		Um nó não-suscetível se tornando suscetível representa a morte de um indivíduo na população ao mesmo tempo de um nascimento. A ideia é que o número da população se permaneça constante durante a simulação, mesmo após uma morte.
	</p>

	<h3>Simulação 1 - Modelo padrão, valor absoluto (10000 nós)</h3>

	<p>
		A seguir, será gerada a primeira simulação que usará o modelo padrão descrito acima. O valor das ordenadas é exibido em valor absoluto e o período estipulado foi de 30 dias. Lembrando que cada dia representa um passo de tempo. 
	</p>

	<div class="row">
		<div class="col col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="panel-title">
						Período exibido: 30 dias
					</div>
				</div>
				<img src="/img/_00_.png" alt="sim" class="img-responsive">
			</div>
		</div>
		<div class="col col-md-4">
			<div class="panel panel-default">
				<ul class="list-group code">
					<li class="list-group-item">K = 1</li>
					<li class="list-group-item">p(I->R) = 0.6</li>
					<li class="list-group-item">p(I->S) = 0.3</li>
					<li class="list-group-item">p(R->S) = 0.1</li>
				</ul>
			</div>
		</div>
	</div>

	<p>
		Como pode-se observar, o resultado da primeira simulação foram três curvas amortecidas para os infectadados, suscetíveis e recuperados. Portanto, os três elementos tendem a um valor em regime permantente.
	</p>

	<p>
		Analizando a simulação, pode-se caracterizar a infecção em dois períodos: o período de propagação - na qual possui um pico - e o período de estabilização que se permanece numa constante. Qual dos períodos será o melhor para aplicar a distribuição de vacinas?
	</p>

	<p>
		No caso do modelo padrão, a doença se iniciou em 18 de março de 2017. Dia 19 de março ela atingiu seu pico de 2500 agentes infectados. Dia 24 de março, a doença atingiu seu declínio formado por 300 agentes. E finalmente, dia 30 de março, a doença se estabilizou com uma média de 400 agentes.
	</p>

	<p>
		Cerca de 2200 de 10000 (22%) indivíduos conseguem ficar longe da contaminação.
	</p>

	<h3>Simulação 2 - Modelo padrão, valor relativo (10000 nós)</h3>

	<p>
		A segunda simulação é a mesma da anterior, porém a partir de agora os valores das ordenadas serão relativos, começando sempre com suscetíveis 99% e infectados 1%.
	</p>

	<div class="row">
		<div class="col col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="panel-title">
						Período exibido: 30 dias
					</div>
				</div>
				<img src="/img/_01_IR60_30.png" alt="sim" class="img-responsive">
			</div>
		</div>
		<div class="col col-md-4">
			<div class="panel panel-default">
				<ul class="list-group code">
					<li class="list-group-item">K = 1</li>
					<li class="list-group-item">p(I->R) = 0.6</li>
					<li class="list-group-item">p(I->S) = 0.3</li>
					<li class="list-group-item">p(R->S) = 0.1</li>
				</ul>
			</div>
		</div>
	</div>

	<h2>Parte 2 - Análises individuais por atributos da doença</h2>

	<p>
		A partir de agora começará diversos testes afim de determinar mínimos e máximos valores para erradicar a doença a partir do modelo padrão.
	</p>

	<h3>Simulação 3</h3>

	<p>
		Seguindo o modelo padrão, o <code class="code">p(I->R)</code> passará de <code class="code">0.6</code> para <code class="code">0.75</code>. Conforme o observado, o pico de infecção reduziu de <code class="code">0.25</code> para <code class="code">0.14</code> e sua estabilidade foi de <code class="code">0.04</code> para <code class="code">0.02</code>. Os suscetíveis cresceram de <code>0.22</code> para <code class="code">0.26</code>.
	</p>

	<div class="row">
		<div class="col col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="panel-title">
						Período exibido: 30 dias
					</div>
				</div>
				<img src="/img/_02_IR75_30.png" alt="sim" class="img-responsive">
			</div>
		</div>
		<div class="col col-md-4">
			<div class="panel panel-default">
				<ul class="list-group code">
					<li class="list-group-item">K = 1</li>
					<li class="list-group-item"><mark>p(I->R) = 0.75</mark></li>
					<li class="list-group-item">p(I->S) = 0.3</li>
					<li class="list-group-item">p(R->S) = 0.1</li>
				</ul>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">Modelo padrão</div>
				<img src="/img/_01_IR60_30.png" alt="sim" class="img-responsive">
			</div>
		</div>
	</div>

	<h3>Simulação 4</h3>

	<p>
		Novamente, aumentou-se o valor do <code class="code">p(I->R)</code>. Agora para <code class="code">90%</code>, resultando para um pico de contaminação de cerca de <code class="code">6%</code>, estabilidade de <code class="code">0.6%</code> e suscetíveis em torno de de <code class="code">32%</code>. Este pode ser considerado a probabilidade iminente da erradicação da doença. Sendo assim, para o grau de contaminação atual da doença, precisa-se de um pouco mais de 90% de chances de cura para erradicar a doença.
	</p>

	<div class="row">
		<div class="col col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="panel-title">
						Período exibido: 30 dias
					</div>
				</div>
				<img src="/img/_03_IR90_30.png" alt="sim" class="img-responsive">
			</div>
		</div>
		<div class="col col-md-4">
			<div class="panel panel-default">
				<ul class="list-group code">
					<li class="list-group-item">K = 1</li>
					<li class="list-group-item"><mark>p(I->R) = 0.90</mark></li>
					<li class="list-group-item">p(I->S) = 0.3</li>
					<li class="list-group-item">p(R->S) = 0.1</li>
				</ul>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">Modelo padrão</div>
				<img src="/img/_01_IR60_30.png" alt="sim" class="img-responsive">
			</div>
		</div>
	</div>

	<h3>Simulação 5</h3>

	<p>
		Para <code class="code">p(I->R) = 95%</code>, a doença se erradica logo de início.
	</p>

	<div class="row">
		<div class="col col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="panel-title">
						Período exibido: 30 dias
					</div>
				</div>
				<img src="/img/_04_IR95_30.png" alt="sim" class="img-responsive">
			</div>
		</div>
		<div class="col col-md-4">
			<div class="panel panel-default">
				<ul class="list-group code">
					<li class="list-group-item">K = 1</li>
					<li class="list-group-item"><mark>p(I->R) = 0.95</mark></li>
					<li class="list-group-item">p(I->S) = 0.3</li>
					<li class="list-group-item">p(R->S) = 0.1</li>
				</ul>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">Modelo padrão</div>
				<img src="/img/_01_IR60_30.png" alt="sim" class="img-responsive">
			</div>
		</div>
	</div>

	<h3>Simulação 6</h3>

	<p>
		Sabendo-se o <code class="code">p(I->R)</code> para a erradicação, chegou a vez agora de descobrir o valor mínimo de <code class="code">k</code> para o mesmo ocorrer. A constante <code class="code">k</code> pode ser utilizada como o grau de contaminação de uma doença, sendo <code class="code">0</code> sem contaminação e <code class="code">1</code> altamente contagiosa. Na simulação 6, o <code class="code">k</code> foi alterado de <code class="code">1</code> para <code class="code">0.5</code>.
	</p>

	<p>
		Houve uma redução de pico de contaminação de <code class="code">25%</code> para <code class="code">21%</code>.
		Estabilidade de contaminação de <code class="code">4%</code> para <code class="code">3.8%</code>. E suscetíveis cresceram de <code class="code">22%</code> para <code class="code">28%</code>.
	</p>

	<div class="row">
		<div class="col col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="panel-title">
						Período exibido: 30 dias
					</div>
				</div>
				<img src="/img/_05_K050_30.png" alt="sim" class="img-responsive">
			</div>
		</div>
		<div class="col col-md-4">
			<div class="panel panel-default">
				<ul class="list-group code">
					<li class="list-group-item"><mark>K = 0.5</mark></li>
					<li class="list-group-item">p(I->R) = 0.6</li>
					<li class="list-group-item">p(I->S) = 0.3</li>
					<li class="list-group-item">p(R->S) = 0.1</li>
				</ul>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">Modelo padrão</div>
				<img src="/img/_01_IR60_30.png" alt="sim" class="img-responsive">
			</div>
		</div>
	</div>

	<h3>Simulação 7</h3>

	<p>
		A constante foi alterada para <code class="code">k = 0.25</code>. Nota-se que a contaminação ficou oscilatória em regime permante. O pico de contaminação ficou em torno de <code class="code">18%</code> e número de infectados e suscetíveis ficou indefinido. 
	</p>

	<p>
		Quando o agente infectoso fica em estado oscilatório em regime permanente, significa que a doença está iminente na sua erradicação, porém isto nunca ocorrerá ao menos que ocorra a distribuição de vacinas. Esse tipo de doença é cíclica e um pequeno número de vacinas é o sufissiente para desaparecer com a doença.
	</p>

	<div class="row">
		<div class="col col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="panel-title">
						Período exibido: 30 dias
					</div>
				</div>
				<img src="/img/_06_K025_30.png" alt="sim" class="img-responsive">
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="panel-title">
						Período exibido: 100 dias
					</div>
				</div>
				<img src="/img/_06_K025_100.png" alt="sim" class="img-responsive">
			</div>
		</div>
		<div class="col col-md-4">
			<div class="panel panel-default">
				<ul class="list-group code">
					<li class="list-group-item"><mark>K = 0.25</mark></li>
					<li class="list-group-item">p(I->R) = 0.6</li>
					<li class="list-group-item">p(I->S) = 0.3</li>
					<li class="list-group-item">p(R->S) = 0.1</li>
				</ul>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">Modelo padrão</div>
				<img src="/img/_01_IR60_30.png" alt="sim" class="img-responsive">
			</div>
		</div>
	</div>

	<h3>Simulação 8</h3>

	<p>
		Reduzindo a constante para <code class="code">k = 0.15</code>, a doença ainda permanece no estado oscilatório, porém o pico reduziu-se para <code class="code">14%</code> e o período dos ciclos aumentou.
	</p>

	<div class="row">
		<div class="col col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="panel-title">
						Período exibido: 30 dias
					</div>
				</div>
				<img src="/img/_07_K015_30.png" alt="sim" class="img-responsive">
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="panel-title">
						Período exibido: 100 dias
					</div>
				</div>
				<img src="/img/_07_K015_100.png" alt="sim" class="img-responsive">
			</div>
		</div>
		<div class="col col-md-4">
			<div class="panel panel-default">
				<ul class="list-group code">
					<li class="list-group-item"><mark>K = 0.15</mark></li>
					<li class="list-group-item">p(I->R) = 0.6</li>
					<li class="list-group-item">p(I->S) = 0.3</li>
					<li class="list-group-item">p(R->S) = 0.1</li>
				</ul>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">Modelo padrão</div>
				<img src="/img/_01_IR60_30.png" alt="sim" class="img-responsive">
			</div>
		</div>
	</div>

	<h3>Simulação 9</h3>

	<p>
		Para o modelo padrão, a constante <code class="code">k = 0.10</code> é o valor mínimo para erradicar a doença.
	</p>

	<div class="row">
		<div class="col col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="panel-title">
						Período exibido: 30 dias
					</div>
				</div>
				<img src="/img/_08_K010_30.png" alt="sim" class="img-responsive">
			</div>
		</div>
		<div class="col col-md-4">
			<div class="panel panel-default">
				<ul class="list-group code">
					<li class="list-group-item"><mark>K = 0.10</mark></li>
					<li class="list-group-item">p(I->R) = 0.6</li>
					<li class="list-group-item">p(I->S) = 0.3</li>
					<li class="list-group-item">p(R->S) = 0.1</li>
				</ul>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">Modelo padrão</div>
				<img src="/img/_01_IR60_30.png" alt="sim" class="img-responsive">
			</div>
		</div>
	</div>

	<h2>Conclusão 1</h2>

	<p>
		Foi analizado o comportamento da doença em dois critérios: grau de infecção e capacidade de cura. Para o modelo padrão, o grau de infecção foi estipulado para <code class="code">k = 1</code> - o valor máximo de contaminação - e a capacidade de cura foi estipulado para <code class="code">p(I->R) = 0.6</code>. 
	</p>

	<p>
		O resutado das simulações constatou maior eficácia na alteração da capacidade de cura. Aumentando-se <code class="code">25%</code> da capacidade de cura, houve uma redução de <code class="code">50%</code> da contaminação em regime permanente. Por outro lado, o grau de contaminação reduziu-se <code class="code">50%</code> e houve uma redução de apenas <code class="code">5%</code> de contaminação em regime permantene.
	</p>

	<p>
		A partir destes dados, pode-se concluir que estabelecer imunidade na população é bem mais eficaz do que se evitar o contagio de uma doença. Em outras palavras, é muito importante que as pessoas se preocupem mais com sua saúde, se alimentando de forma saudável, praticando mais atividades físicas e tomando melhores práticas de higiene. Sendo assim, surtos de contaminação poderão ser evitado.
	</p>

	<h2>Parte 3 - Realizando testes de vacinação</h2>

	<p>
		As próximas simulações serão usadas para estudos sobre três diferentes distribuições de vacinas: distribuição aleatória, de maior grau e de vizinhos de maior grau. O modelo padrão contunuará a ser usado como referência. A finalidade do estudo continua a explorar as estratégias mais eficazes em extinguir uma doença, mas dessa vez usando a distribuição de vacinas. 
	</p>

	<p>
		A distribuição aleatório como o próprio nome diz, as vacinas serão aplicadas aos nós de maneira randômica, ou seja, a cada passo de tempo, um determinado número de nós serão escolhidos para serem vacinados. 
	</p>

	<p>
		A distribuição de maior grau consiste em vacinar os nós suscetíveis na ordem dos que tiverem um maior número de vizinhos infectados.
	</p>

	<p>
		A distribuição de vizinhos de maior grau consiste em vacinar os nós dos vizinhos suscetíveis dos nós de maior grau, ou seja, serão vacinados os vizinhos dos nós que possuirem o maior número de vizinhos infectados. 
	</p>

	<p>
		Além do fator distribuição, também serão explorados fatores como o limite populacional de distribuição e a taxa de vacinação e o período da doença (propagação e estabilização) em que a inoculação será aplicada.
	</p>

	<!------------------- MARK 1 ------------------>
	<h2>Período 100 dias de vacinação</h2>

	<p>
		A seguir, os primeiros testes serão investigados a erradicação da doença num período de 100 dias constantes de vacinação. Por ser um longo período, as taxas de vacinação diária serão baixos.
	</p>

	<section>
		<h3>Simulação 10</h3>

		<p>
			A décima simulação constitui-se em vacinar apenas <code class="code">10%</code> da população (1000) usando a distribuição aleatória durante a fase de propagação da doença. Como o período de inoculação é de <code class="code">100 dias</code>, a taxa de vacinação nesse caso será de 10 indivíduos vacinados por dia. 
		</p>

		<p>
			O resultado dessa simulação mostrou que essa estratégia não foi eficaz e praticamente não obteve um grande efeito no comportamento da doença.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 150 dias
						</div>
					</div>
					<img src="/img/_09_V1_10_100.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Aleatória</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 10% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 10 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 100 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 11</h3>

		<p>
			A próxima simulação será aumentado o limite de distribuição de <code class="code">10%</code> para <code class="code">30%</code> da população. Por conta dessa alteração, a taxa de vacinação subirá para <code class="code">30 / dia</code>.
		</p>

		<p>
			Nesse caso, a estabilidade da doença foi consideradamente alterada, passando de <code class="code">4%</code> para <code class="code">2.5%</code>. Uma redução de <code class="code">37.5%</code>.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 150 dias
						</div>
					</div>
					<img src="/img/_09_V1_30_100.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Aleatória</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 30% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 30 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 100 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 12</h3>

		<p>
			Com o limite de distribuição aumentado para <code class="code">50%</code>, a estabilidade da doença reduziu-se para cerca de <code class="code">1.7%</code>.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 150 dias
						</div>
					</div>
					<img src="/img/_09_V1_50_100.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Aleatória</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 50% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 50 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 100 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 13</h3>

		<p>
			Com <code class="code">70%</code> da população vacinada em 100 dias, essa doença altamente contagiosa (<code class="code">k = 1</code>) ainda insiste em não se erradicar, permanecendo em cerca de <code class="code">1%</code> da população.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 150 dias
						</div>
					</div>
					<img src="/img/_09_V1_70_100.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Aleatória</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 70% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 70 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 100 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 14</h3>

		<p>
			Finalmente, a doença é erradicada quando <code class="code">80%</code> da população foi vacinada.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 150 dias
						</div>
					</div>
					<img src="/img/_09_V1_80_100.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Aleatória</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 80% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 80 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 100 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 15</h3>

		<p>
			Para a próxima simulação, o período de 100 dias de inoculação continuará a ser usado, porém o método de distribuição a ser utilizado agora será o de <code class="code">Maior Grau</code>. 
		</p>

		<p>
			O primeiro teste limita a distribuição em <code class="code">10%</code>. O resultado foi praticamente o mesmo que a distribuição <code class="code">Aleatória</code>: ineficaz.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 150 dias
						</div>
					</div>
					<img src="/img/_10_V2_10_100.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 10% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 10 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 100 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 16</h3>

		<p>
			A partir de <code class="code">30%</code> de limite, a distribuição <code class="code">Maior Grau</code> começa a se prevalecer. Enquanto a distribuição <code class="code">Aleatória</code> a doença se permaneceu em <code class="code">2.5%</code>, esta ficou em <code class="code">2.2%</code>.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 150 dias
						</div>
					</div>
					<img src="/img/_10_V2_30_100.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 30% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 30 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 100 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 17</h3>

		<p>
			Quando <code class="code">50%</code> foi vacinado em 100 dias na distribuição <code class="code">Maior Grau</code>, a doença erradicou-se. Na distribuição <code class="code">Aleatória</code>, precisou-se vacinar 80% da população para erradicar a mesma doença.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 150 dias
						</div>
					</div>
					<img src="/img/_10_V2_50_100.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 50% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 50 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 100 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 18</h3>

		<p>
			Ainda no período de 100 dias, será testada a última distribuição: a <code class="code">Vizinhos de Maior Grau</code>. Para primeiro teste, sempre será utilizado um limite de <code class="code">10%</code> de distribuição.
		</p>

		<p>
			Novamente <code class="code">10%</code> de limite ainda é ineficaz, mesmo para essa distribuição (apesar dessa provocar um maior efeito em relação às outras).
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 150 dias
						</div>
					</div>
					<img src="/img/_11_V3_10_100.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Vizinhos de Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 10% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 10 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 100 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 19</h3>

		<p>
			Limitado agora para <code class="code">30%</code>, a doença permaneceu em <code class="code">2.8%</code>.
			Lembrando que a distribuição <code class="code">Aleatória</code> ficou em <code class="code">2.5%</code> e a distribuição <code class="code">Maior Grau</code>, <code class="code">2.2%</code>.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 150 dias
						</div>
					</div>
					<img src="/img/_11_V3_30_100.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Vizinhos de Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 30% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 30 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 100 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 20</h3>

		<p>
			Em 50% de limite, a doença ficou em torno de <code class="code">1.3%</code>. Na distribuição <code class="code">Aleatória</code>, <code class="code">1.7%</code> e na distribuição <code class="code">Maior Grau</code>, a doença foi erradicada.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 150 dias
						</div>
					</div>
					<img src="/img/_11_V3_50_100.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Vizinhos de Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 50% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 50 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 100 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 21</h3>

		<p>
			A partir de <code class="code">60%</code> de vacinados em 100 dias, a doença é erradicada para a distribuição <code class="code">Vizinhos de Maior Grau</code>.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 150 dias
						</div>
					</div>
					<img src="/img/_11_V3_60_100.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Vizinhos de Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 60% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 60 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 100 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>	
	</section>

	<!------------------- MARK 2 ------------------>
	<h2>Período 100 dias de vacinação na fase de estabilização</h2>

	<p>
		Os próximos teste agora serão iguais aos anteriores, porém a única diferença é que as distribuições de vacinas serão aplicadas no período de estabilidade da doença.
	</p>

	<p>
		O objetivo destes testes é verificar se há de alguma forma diferença entre vacinar durante a <ins>fase de propagação</ins> e a <code><ins>fase de estabilização</ins></code> da doença.
	</p>

	<section>
		<h3>Simulação 22</h3>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 150 dias
						</div>
					</div>
					<img src="/img/_12_V1_10_100_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Aleatória</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 10% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 10 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 100 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 23</h3>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 150 dias
						</div>
					</div>
					<img src="/img/_12_V1_30_100_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Aleatória</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 30% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 30 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 100 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 24</h3>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 150 dias
						</div>
					</div>
					<img src="/img/_12_V1_50_100_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Aleatória</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 50% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 50 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 100 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 25</h3>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 150 dias
						</div>
					</div>
					<img src="/img/_12_V1_70_100_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Aleatória</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 70% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 70 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 100 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 26</h3>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 150 dias
						</div>
					</div>
					<img src="/img/_12_V1_80_100_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Aleatória</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 80% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 80 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 100 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 27</h3>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 150 dias
						</div>
					</div>
					<img src="/img/_13_V2_10_100_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 10% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 10 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 100 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 28</h3>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 150 dias
						</div>
					</div>
					<img src="/img/_13_V2_30_100_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 30% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 30 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 100 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 29</h3>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 150 dias
						</div>
					</div>
					<img src="/img/_13_V2_50_100_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 50% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 50 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 100 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 30</h3>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 150 dias
						</div>
					</div>
					<img src="/img/_14_V3_10_100_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Vizinhos de Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 10% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 10 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 100 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 31</h3>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 150 dias
						</div>
					</div>
					<img src="/img/_14_V3_30_100_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Vizinhos de Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 30% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 30 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 100 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 32</h3>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 150 dias
						</div>
					</div>
					<img src="/img/_14_V3_50_100_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Vizinhos de Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 50% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 50 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 100 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 33</h3>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 150 dias
						</div>
					</div>
					<img src="/img/_14_V3_60_100_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Vizinhos de Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 60% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 60 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 100 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<h2>Resultado 1 - 100 dias de vacinação</h2>

	<p>
		Com os resultados obtidos no experimento de diferentes estratégias distribuições no período de 100 dias, agora é possível fazer um comparativo. As simulações a seguir são os resultados anteriores na qual ocorreu erradicação da doença.
	</p>

	<h3>Experimentos em que a doença se extinguiu em 100 dias de vacinação</h3>

	<div class="row">
		<div class="col col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<b>Distribuição:</b> <code>Aleatória</code>
				</div>
				<img src="/img/_09_V1_80_100.png" alt="sim" class="img-responsive">
				<ul class="list-group code">
					<li class="list-group-item"><b>Limite:</b> 80% da população</li>
					<li class="list-group-item"><b>Taxa de vacinação:</b> 80 / dia</li>
					<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
				</ul>
			</div>
		</div>
		<div class="col col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<b>Distribuição:</b> <code>Maior Grau</code>
				</div>
				<img src="/img/_10_V2_50_100.png" alt="sim" class="img-responsive">
				<ul class="list-group code">
					<li class="list-group-item"><b>Limite:</b> 50% da população</li>
					<li class="list-group-item"><b>Taxa de vacinação:</b> 50 / dia</li>
					<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
				</ul>
			</div>
		</div>
		<div class="col col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<b>Distribuição:</b> <code>Vizinhos de Maior Grau</code>
				</div>
				<img src="/img/_11_V3_60_100.png" alt="sim" class="img-responsive">
				<ul class="list-group code">
					<li class="list-group-item"><b>Limite:</b> 60% da população</li>
					<li class="list-group-item"><b>Taxa de vacinação:</b> 60 / dia</li>
					<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<b>Distribuição:</b> <code>Aleatória</code>
				</div>
				<img src="/img/_12_V1_80_100_d10.png" alt="sim" class="img-responsive">
				<ul class="list-group code">
					<li class="list-group-item"><b>Limite:</b> 80% da população</li>
					<li class="list-group-item"><b>Taxa de vacinação:</b> 80 / dia</li>
					<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
				</ul>
			</div>
		</div>
		<div class="col col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<b>Distribuição:</b> <code>Maior Grau</code>
				</div>
				<img src="/img/_13_V2_50_100_d10.png" alt="sim" class="img-responsive">
				<ul class="list-group code">
					<li class="list-group-item"><b>Limite:</b> 50% da população</li>
					<li class="list-group-item"><b>Taxa de vacinação:</b> 50 / dia</li>
					<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
				</ul>
			</div>
		</div>
		<div class="col col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<b>Distribuição:</b> <code>Vizinhos de Maior Grau</code>
				</div>
				<img src="/img/_14_V3_60_100_d10.png" alt="sim" class="img-responsive">
				<ul class="list-group code">
					<li class="list-group-item"><b>Limite:</b> 60% da população</li>
					<li class="list-group-item"><b>Taxa de vacinação:</b> 60 / dia</li>
					<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
				</ul>
			</div>
		</div>
	</div>

	<p>
		Analizando as simulações anteriores, foi constado maior eficácia na distribuição <code class="code">Maior Grau</code>, necessitando <code class="code">50%</code> da população vacinada. Em segundo lugar ficou para a distribuição <code class="code">Vizinhos de Maior Grau</code>, com <code class="code">60%</code> da população vacinada. A distribuição <code class="code">Aleatória</code>, como o esperado, foi a menos eficaz, precisando vacianar <code class="code">80%</code> da população para erradicar a doença. 
	</p>

	<p>
		Em relação ao período da doença na qual foi aplicada a inoculação, não houve diferenças relevantes. Portanto, para um período de 100 dias de inoculação, não há diferenças na fase da doença em que será aplicadas as vacinas.
	</p>
	
	<!------------------- MARK 3 ------------------>
	<h2>Período 30 dias de vacinação</h2>

	<p>
		A seguir, os testes anteriores serão novamente repetidos, mas agora o período de inoculação será reduzido para 30 dias. Consequentemente, as taxas de vacinações diárias serão aumentadas em <code class="code">3.3 vezes</code> em relação ao período anterior. 
	</p>

	<section>
		<h3>Simulação 34</h3>

		<p>
			<ins>Estabilidade da doença</ins> em 30 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">3.1%</code> com <code class="code">10%</code> de vacinados.
		</p>
		<p>Comparando: </p>
		<p>
			<ins>Estabilidade da doença</ins> em 100 dias de vacinação: Não houve uma redução significativa.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 60 dias
						</div>
					</div>
					<img src="/img/_15_V1_10_30.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Aleatória</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 10% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 33 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 30 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 35</h3>

		<p>
			<ins>Estabilidade da doença</ins> em 30 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">2.3%</code> com <code class="code">30%</code> de vacinados.
		</p>
		<p>Comparando:</p>
		<p>
			<ins>Estabilidade da doença</ins> em 100 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">2.5%</code> com <code class="code">30%</code> de vacinados.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 60 dias
						</div>
					</div>
					<img src="/img/_15_V1_30_30.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Aleatória</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 30% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 100 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 30 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 36</h3>

		<p>
			<ins>Estabilidade da doença</ins> em 30 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">1.0%</code> com <code class="code">50%</code> de vacinados.
		</p>
		<p>Comparando:</p>
		<p>
			<ins>Estabilidade da doença</ins> em 100 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">1.7%</code> com <code class="code">50%</code> de vacinados.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 60 dias
						</div>
					</div>
					<img src="/img/_15_V1_50_30.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Aleatória</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 50% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 167 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 30 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 37</h3>

		<p>
			<ins>Estabilidade da doença</ins> em 30 dias de vacinação: A doença erradicou-se com <code class="code">70%</code> de vacinados.
		</p>
		<p>Comparando:</p>
		<p>
			<ins>Estabilidade da doença</ins> em 100 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">1%</code> com <code class="code">70%</code> de vacinados.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 60 dias
						</div>
					</div>
					<img src="/img/_15_V1_70_30.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Aleatória</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 70% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 233 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 30 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 38</h3>

		<p>
			<ins>Estabilidade da doença</ins> em 30 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">3.6%</code> com <code class="code">10%</code> de vacinados.
		</p>
		<p>Comparando:</p>
		<p>
			<ins>Estabilidade da doença</ins> em 100 dias de vacinação: Praticamente o mesmo resultado.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 60 dias
						</div>
					</div>
					<img src="/img/_16_V2_10_30.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 10% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 33 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 30 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 39</h3>

		<p>
			<ins>Estabilidade da doença</ins> em 30 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">2.3%</code> com <code class="code">30%</code> de vacinados.
		</p>
		<p>Comparando:</p>
		<p>
			<ins>Estabilidade da doença</ins> em 100 dias de vacinação: Praticamente o mesmo resultado.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 60 dias
						</div>
					</div>
					<img src="/img/_16_V2_30_30.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 30% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 100 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 30 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 40</h3>

		<p>
			<ins>Estabilidade da doença</ins> em 30 dias de vacinação: A doença erradicou-se com <code class="code">40%</code> de vacinados.
		</p>
		<p>Comparando:</p>
		<p>
			<ins>Estabilidade da doença</ins> em 100 dias de vacinação: A doença erradicou-se com <code class="code">50%</code> de vacinados.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 60 dias
						</div>
					</div>
					<img src="/img/_16_V2_40_30.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 40% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 133 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 30 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 41</h3>

		<p>
			<ins>Estabilidade da doença</ins> em 30 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">3.3%</code> com <code class="code">10%</code> de vacinados.
		</p>
		<p>Comparando:</p>
		<p>
			<ins>Estabilidade da doença</ins> em 100 dias de vacinação: Praticamente o mesmo resultado.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 60 dias
						</div>
					</div>
					<img src="/img/_17_V3_10_30.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Vizinhos de Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 10% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 33 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 30 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 42</h3>

		<p>
			<ins>Estabilidade da doença</ins> em 30 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">2.1%</code> com <code class="code">30%</code> de vacinados.
		</p>
		<p>Comparando:</p>
		<p>
			<ins>Estabilidade da doença</ins> em 100 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">2.8%</code> com <code class="code">30%</code> de vacinados.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 60 dias
						</div>
					</div>
					<img src="/img/_17_V3_30_30.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Vizinhos de Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 30% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 100 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 30 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 43</h3>

		<p>
			<ins>Estabilidade da doença</ins> em 30 dias de vacinação: A doença foi erradicada com <code class="code">50%</code> da população vacinada.
		</p>
		<p>Comparando:</p>
		<p>
			<ins>Estabilidade da doença</ins> em 100 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">1.3%</code> com <code class="code">50%</code> de vacinados.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 60 dias
						</div>
					</div>
					<img src="/img/_17_V3_50_30.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Vizinhos de Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 50% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 167 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 30 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<!------------------- MARK 4 ------------------>
	<h2>Período 30 dias de vacinação na fase de estabilização</h2>

	<p>
		Os próximos teste agora serão iguais aos anteriores, porém a única diferença é que as distribuições de vacinas serão aplicadas no período de estabilidade da doença.
	</p>
	<p>
		O objetivo destes testes é verificar se há de alguma forma diferença entre vacinar durante a fase de propagação e a fase de estabilização da doença.
	</p>

	<section>
		<h3>Simulação 44</h3>


		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 60 dias
						</div>
					</div>
					<img src="/img/_18_V1_10_30_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Aleatória</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 10% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 33 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 30 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 45</h3>

		<p></p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 60 dias
						</div>
					</div>
					<img src="/img/_18_V1_30_30_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Aleatória</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 30% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 100 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 30 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 46</h3>

		<p></p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 60 dias
						</div>
					</div>
					<img src="/img/_18_V1_50_30_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Aleatória</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 50% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 167 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 30 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 47</h3>

		<p></p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 60 dias
						</div>
					</div>
					<img src="/img/_18_V1_70_30_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Aleatória</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 70% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 233 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 30 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 48</h3>

		<p></p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 60 dias
						</div>
					</div>
					<img src="/img/_19_V2_10_30_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 10% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 33 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 30 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 49</h3>

		<p></p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 60 dias
						</div>
					</div>
					<img src="/img/_19_V2_30_30_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 30% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 100 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 30 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 50</h3>

		<p></p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 60 dias
						</div>
					</div>
					<img src="/img/_19_V2_40_30_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 40% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 133 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 30 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 51</h3>

		<p></p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 60 dias
						</div>
					</div>
					<img src="/img/_20_V3_10_30_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Vizinhos de Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 10% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 33 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 30 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 52</h3>

		<p></p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 60 dias
						</div>
					</div>
					<img src="/img/_20_V3_30_30_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Vizinhos de Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 30% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 100 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 30 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 53</h3>

		<p></p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 60 dias
						</div>
					</div>
					<img src="/img/_20_V3_50_30_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Vizinhos de Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 50% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 167 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 30 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<h2>Resultado 2 - 30 dias de vacinação</h2>

	<p>
		Os resultados obtidos no experimento das diferentes estratégias distribuições - no período de 30 dias - permitem fazer um comparativo. As simulações a seguir são os resultados anteriores na qual ocorreu erradicação da doença.
	</p>

	<h3>Experimentos em que a doença se extinguiu em 30 dias de vacinação</h3>

	<div class="row">
		<div class="col col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<b>Distribuição:</b> <code>Aleatória</code>
				</div>
				<img src="/img/_15_V1_70_30.png" alt="sim" class="img-responsive">
				<ul class="list-group code">
					<li class="list-group-item"><b>Limite:</b> 70% da população</li>
					<li class="list-group-item"><b>Taxa de vacinação:</b> 233 / dia</li>
					<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
				</ul>
			</div>
		</div>
		<div class="col col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<b>Distribuição:</b> <code>Maior Grau</code>
				</div>
				<img src="/img/_16_V2_40_30.png" alt="sim" class="img-responsive">
				<ul class="list-group code">
					<li class="list-group-item"><b>Limite:</b> 40% da população</li>
					<li class="list-group-item"><b>Taxa de vacinação:</b> 133 / dia</li>
					<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
				</ul>
			</div>
		</div>
		<div class="col col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<b>Distribuição:</b> <code>Vizinhos de Maior Grau</code>
				</div>
				<img src="/img/_17_V3_50_30.png" alt="sim" class="img-responsive">
				<ul class="list-group code">
					<li class="list-group-item"><b>Limite:</b> 50% da população</li>
					<li class="list-group-item"><b>Taxa de vacinação:</b> 167 / dia</li>
					<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<b>Distribuição:</b> <code>Aleatória</code>
				</div>
				<img src="/img/_18_V1_70_30_d10.png" alt="sim" class="img-responsive">
				<ul class="list-group code">
					<li class="list-group-item"><b>Limite:</b> 70% da população</li>
					<li class="list-group-item"><b>Taxa de vacinação:</b> 233 / dia</li>
					<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
				</ul>
			</div>
		</div>
		<div class="col col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<b>Distribuição:</b> <code>Maior Grau</code>
				</div>
				<img src="/img/_19_V2_40_30_d10.png" alt="sim" class="img-responsive">
				<ul class="list-group code">
					<li class="list-group-item"><b>Limite:</b> 40% da população</li>
					<li class="list-group-item"><b>Taxa de vacinação:</b> 133 / dia</li>
					<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
				</ul>
			</div>
		</div>
		<div class="col col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<b>Distribuição:</b> <code>Vizinhos de Maior Grau</code>
				</div>
				<img src="/img/_20_V3_50_30_d10.png" alt="sim" class="img-responsive">
				<ul class="list-group code">
					<li class="list-group-item"><b>Limite:</b> 50% da população</li>
					<li class="list-group-item"><b>Taxa de vacinação:</b> 167 / dia</li>
					<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
				</ul>
			</div>
		</div>
	</div>

	<p>
		Reduzindo o período de inoculação para 30 dias, a distribuição <code class="code">Maior Grau</code> continua sendo a mais eficaz, com <code class="code">40%</code> de vacinados, seguido por distribuição <code class="code">Vizinhos de Maior Grau</code> com <code class="code">50%</code> e distribuição <code class="code">Aleatória</code> com <code class="code">70%</code>. 
	</p>
	<p>
		Nesta análise, as taxas de vacinação foi aumentado para <code class="code">333%</code> (aproximadamente) em relação à anterior. O resultado disso foi a redução do número de vacinas em <code class="code">12.5%</code> para a distribuição <code class="code">Aleatória</code>, <code class="code">16.7%</code> para a distribuição <code class="code">Vizinhos de Maior Grau</code> e <code class="code">20%</code> para a distribuição <code class="code">Maior Grau</code>.
	</p>
	<p>
		Em relação ao período da doença na qual foi aplicada a inoculação, não houve diferenças relevantes. Portanto, para um período de 30 dias de inoculação, não há diferenças na fase da doença em que será aplicadas as vacinas.
	</p>

	<!------------------- MARK 5 ------------------>
	<h2>Período 7 dias de vacinação</h2>

	<p>
		A seguir, os testes anteriores serão novamente repetidos, mas agora o período de inoculação será reduzido para 7 dias. Consequentemente, as taxas de vacinações diárias serão aumentadas em <code class="code">14.28 vezes</code> em relação ao período de 100 dias.
	</p>

	<section>
		<h3>Simulação 54</h3>

		<p>
			<ins>Estabilidade da doença</ins> em 7 dias de vacinação: Não houve uma redução significativa.
		</p>
		<p>Comparando:</p>
		<p>
			<ins>Estabilidade da doença</ins> em 30 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">3.1%</code> com <code class="code">10%</code> de vacinados.
		</p>
		<p>
			<ins>Estabilidade da doença</ins> em 100 dias de vacinação: Não houve uma redução significativa.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 30 dias
						</div>
					</div>
					<img src="/img/_21_V1_10_7.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Aleatória</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 10% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 143 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 7 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 55</h3>

		<p>
			<ins>Estabilidade da doença</ins> em 7 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">2.4%</code> com <code class="code">30%</code> de vacinados.
		</p>
		<p>Comparando:</p>
		<p>
			<ins>Estabilidade da doença</ins> em 30 dias de vacinação: praticamente o mesmo resultado. 
		</p>
		<p>
			<ins>Estabilidade da doença</ins> em 100 dias de vacinação: praticamente o mesmo resultado.
		</p>

		<p>Nota-se que no ápice do declínio, doença quase é erradicada. Porém, ela acaba se estabilizando em uma constante. Esse comportamento é típico de uma inoculação agressiva.</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 30 dias
						</div>
					</div>
					<img src="/img/_21_V1_30_7.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Aleatória</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 30% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 429 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 7 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 56</h3>

		<p>
			<ins>Estabilidade da doença</ins> em 7 dias de vacinação: A doença foi erradicada com <code class="code">50%</code> da população vacinada.
		</p>
		<p>Comparando:</p>
		<p>
			<ins>Estabilidade da doença</ins> em 30 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">1.0%</code> com <code class="code">50%</code> de vacinados.
		</p>
		<p>
			<ins>Estabilidade da doença</ins> em 100 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">1.7%</code> com <code class="code">50%</code> de vacinados.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 30 dias
						</div>
					</div>
					<img src="/img/_21_V1_50_7.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Aleatória</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 50% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 714 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 7 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 57</h3>

		<p>
			<ins>Estabilidade da doença</ins> em 7 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">3.3%</code> com <code class="code">10%</code> de vacinados.
		</p>
		<p>Comparando:</p>
		<p>
			<ins>Estabilidade da doença</ins> em 30 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">3.1%</code> com <code class="code">10%</code> de vacinados.
		</p>
		<p>
			<ins>Estabilidade da doença</ins> em 100 dias de vacinação: Não houve uma redução significativa.
		</p>
		<p>
			Novamente a doença quase é erradicada no ápice do declínio.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 30 dias
						</div>
					</div>
					<img src="/img/_22_V2_10_7.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 10% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 143 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 7 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 58</h3>

		<p>
			<ins>Estabilidade da doença</ins> em 7 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">2.4%</code> com <code class="code">30%</code> de vacinados.
		</p>
		<p>Comparando:</p>
		<p>
			<ins>Estabilidade da doença</ins> em 30 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">2.3%</code> com <code class="code">30%</code> de vacinados.
		</p>
		<p>
			<ins>Estabilidade da doença</ins> em 100 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">2.2%</code> com <code class="code">30%</code> de vacinados.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 30 dias
						</div>
					</div>
					<img src="/img/_22_V2_30_7.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 30% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 429 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 7 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 59</h3>

		<p>
			<ins>Estabilidade da doença</ins> em 7 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">1.6%</code> com <code class="code">40%</code> de vacinados.
		</p>
		<p>Comparando:</p>
		<p>
			<ins>Estabilidade da doença</ins> em 30 dias de vacinação: a doença foi erradicada com <code class="code">40%</code> de vacinados.
		</p>
		<p>
			<ins>Estabilidade da doença</ins> em 100 dias de vacinação: a doença foi erradicada com <code class="code">50%</code> de vacinados.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 30 dias
						</div>
					</div>
					<img src="/img/_22_V2_40_7.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 40% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 571 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 7 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 60</h3>

		<p>
			<ins>Estabilidade da doença</ins> em 7 dias de vacinação: a doença foi erradicada com <code class="code">50%</code> de vacinados.
		</p>
		<p>Comparando:</p>
		<p>
			<ins>Estabilidade da doença</ins> em 30 dias de vacinação: a doença foi erradicada com <code class="code">40%</code> de vacinados.
		</p>
		<p>
			<ins>Estabilidade da doença</ins> em 100 dias de vacinação: a doença foi erradicada com <code class="code">50%</code> de vacinados.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 30 dias
						</div>
					</div>
					<img src="/img/_22_V2_50_7.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 50% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 714 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 7 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 61</h3>

		<p>
			<ins>Estabilidade da doença</ins> em 7 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">3.1%</code> com <code class="code">10%</code> de vacinados.
		</p>
		<p>Comparando:</p>
		<p>
			<ins>Estabilidade da doença</ins> em 30 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">3.3%</code> com <code class="code">10%</code> de vacinados.
		</p>
		<p>
			<ins>Estabilidade da doença</ins> em 100 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">3.3%</code> com <code class="code">10%</code> de vacinados.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 30 dias
						</div>
					</div>
					<img src="/img/_23_V3_10_7.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Vizinhos de Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 10% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 143 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 7 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 62</h3>

		<p>
			<ins>Estabilidade da doença</ins> em 7 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">2.3%</code> com <code class="code">30%</code> de vacinados.
		</p>
		<p>Comparando:</p>
		<p>
			<ins>Estabilidade da doença</ins> em 30 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">2.1%</code> com <code class="code">30%</code> de vacinados.
		</p>
		<p>
			<ins>Estabilidade da doença</ins> em 100 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">2.8%</code> com <code class="code">30%</code> de vacinados.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 30 dias
						</div>
					</div>
					<img src="/img/_23_V3_30_7.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Vizinhos de Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 30% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 429 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 7 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 63</h3>

		<p>
			<ins>Estabilidade da doença</ins> em 7 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">1.8%</code> com <code class="code">40%</code> de vacinados.
		</p>
		<p>Comparando:</p>
		<p>
			<ins>Estabilidade da doença</ins> em 30 dias de vacinação: a doença foi erradicada com <code class="code">50%</code> de vacinados.
		</p>
		<p>
			<ins>Estabilidade da doença</ins> em 100 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">1.3%</code> com <code class="code">50%</code> de vacinados.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 30 dias
						</div>
					</div>
					<img src="/img/_23_V3_40_7.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Vizinhos de Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 40% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 571 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 7 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 64</h3>

		<p>
			<ins>Estabilidade da doença</ins> em 7 dias de vacinação: a doença foi erradicada com <code class="code">50%</code> de vacinados.
		</p>
		<p>Comparando:</p>
		<p>
			<ins>Estabilidade da doença</ins> em 30 dias de vacinação: a doença foi erradicada com <code class="code">50%</code> de vacinados.
		</p>
		<p>
			<ins>Estabilidade da doença</ins> em 100 dias de vacinação: reduziu de <code class="code">4%</code> para <code class="code">1.3%</code> com <code class="code">50%</code> de vacinados.
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 30 dias
						</div>
					</div>
					<img src="/img/_23_V3_50_7.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Vizinhos de Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 50% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 714 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 7 dias</li>
						<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<!------------------- MARK 6 ------------------>
	<h2>Período 7 dias de vacinação na fase de estabilização</h2>

	<p>
		Os próximos teste agora serão iguais aos anteriores, porém a única diferença é que as distribuições de vacinas serão aplicadas no período de estabilidade da doença.
	</p>
	<p>
		O objetivo destes testes é verificar se há de alguma forma diferença entre vacinar durante a fase de propagação e a fase de estabilização da doença.
	</p>

	<section>
		<h3>Simulação 65</h3>

		<p></p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 30 dias
						</div>
					</div>
					<img src="/img/_24_V1_10_7_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Aleatória</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 10% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 143 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 7 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 66</h3>

		<p></p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 30 dias
						</div>
					</div>
					<img src="/img/_24_V1_30_7_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Aleatória</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 30% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 429 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 7 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 67</h3>

		<p></p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 30 dias
						</div>
					</div>
					<img src="/img/_24_V1_50_7_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Aleatória</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 50% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 714 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 7 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 68</h3>

		<p></p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 30 dias
						</div>
					</div>
					<img src="/img/_25_V2_10_7_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 10% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 143 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 7 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 69</h3>

		<p>
			Pela primeira vez, nota-se diferença de iniciar a inoculação no período de estabilização da doença. A doença foi erradicada com <code class="code">30%</code> de vacinados, enquanto precisou-se de <code class="code">50%</code> de vacinados para erradicá-la nas mesmas condições. 
		</p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 30 dias
						</div>
					</div>
					<img src="/img/_25_V2_30_7_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 30% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 429 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 7 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 70</h3>

		<p></p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 30 dias
						</div>
					</div>
					<img src="/img/_26_V3_10_7_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Vizinhos de Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 10% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 143 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 7 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 71</h3>

		<p></p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 30 dias
						</div>
					</div>
					<img src="/img/_26_V3_30_7_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Vizinhos de Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 30% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 429 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 7 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 72</h3>

		<p></p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 30 dias
						</div>
					</div>
					<img src="/img/_26_V3_40_7_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Vizinhos de Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 40% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 571 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 7 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>

		<h3>Simulação 73</h3>

		<p></p>

		<div class="row">
			<div class="col col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							Período exibido: 30 dias
						</div>
					</div>
					<img src="/img/_26_V3_50_7_d10.png" alt="sim" class="img-responsive">
				</div>
			</div>
			<div class="col col-md-4">
				<div class="panel panel-default">
					<ul class="list-group code">
						<li class="list-group-item"><b>Distribuição:</b> <code>Vizinhos de Maior Grau</code></li>
						<li class="list-group-item"><mark><b>Limite:</b> 50% da população</mark></li>
						<li class="list-group-item"><mark><b>Taxa de vacinação:</b> 714 / dia</mark></li>
						<li class="list-group-item"><b>Período de vacinação:</b> 7 dias</li>
						<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<h2>Resultado 3 - 7 dias de vacinação</h2>

	<p>
		Os resultados obtidos no experimento das diferentes estratégias distribuições - no período de 7 dias - permitem fazer um comparativo. As simulações a seguir são os resultados anteriores na qual ocorreu erradicação da doença.
	</p>

	<h3>
		Experimentos em que a doença se extinguiu em 7 dias de vacinação
	</h3>

	<div class="row">
		<div class="col col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<b>Distribuição:</b> <code>Aleatória</code>
				</div>
				<img src="/img/_21_V1_50_7.png" alt="sim" class="img-responsive">
				<ul class="list-group code">
					<li class="list-group-item"><b>Limite:</b> 50% da população</li>
					<li class="list-group-item"><b>Taxa de vacinação:</b> 714 / dia</li>
					<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
				</ul>
			</div>
		</div>
		<div class="col col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<b>Distribuição:</b> <code>Maior Grau</code>
				</div>
				<img src="/img/_22_V2_50_7.png" alt="sim" class="img-responsive">
				<ul class="list-group code">
					<li class="list-group-item"><b>Limite:</b> 50% da população</li>
					<li class="list-group-item"><b>Taxa de vacinação:</b> 714 / dia</li>
					<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
				</ul>
			</div>
		</div>
		<div class="col col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<b>Distribuição:</b> <code>Vizinhos de Maior Grau</code>
				</div>
				<img src="/img/_23_V3_50_7.png" alt="sim" class="img-responsive">
				<ul class="list-group code">
					<li class="list-group-item"><b>Limite:</b> 50% da população</li>
					<li class="list-group-item"><b>Taxa de vacinação:</b> 714 / dia</li>
					<li class="list-group-item"><ins>Inoculação na fase de dissipação</ins></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<b>Distribuição:</b> <code>Aleatória</code>
				</div>
				<img src="/img/_24_V1_50_7_d10.png" alt="sim" class="img-responsive">
				<ul class="list-group code">
					<li class="list-group-item"><b>Limite:</b> 50% da população</li>
					<li class="list-group-item"><b>Taxa de vacinação:</b> 714 / dia</li>
					<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
				</ul>
			</div>
		</div>
		<div class="col col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<b>Distribuição:</b> <code>Maior Grau</code>
				</div>
				<img src="/img/_25_V2_30_7_d10.png" alt="sim" class="img-responsive">
				<ul class="list-group code">
					<li class="list-group-item"><b>Limite:</b> 30% da população</li>
					<li class="list-group-item"><b>Taxa de vacinação:</b> 429 / dia</li>
					<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
				</ul>
			</div>
		</div>
		<div class="col col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<b>Distribuição:</b> <code>Vizinhos de Maior Grau</code>
				</div>
				<img src="/img/_26_V3_50_7_d10.png" alt="sim" class="img-responsive">
				<ul class="list-group code">
					<li class="list-group-item"><b>Limite:</b> 50% da população</li>
					<li class="list-group-item"><b>Taxa de vacinação:</b> 714 / dia</li>
					<li class="list-group-item"><code><ins>Inoculação na fase de estabilização</ins></code></li>
				</ul>
			</div>
		</div>
	</div>

	<p>
		Nesta análise, as taxas de vacinação foi aumentado para 1429% (aproximadamente) em relação ao período de 100 dias e 476% em relação ao período de 30 dias. Para a distribuição <code class="code">Aleatória</code>, houve uma redução de <code class="code">28.5%</code> do número de vacinas. Porém, para as distribuições <code class="code">Maior Grau</code> e <code class="code">Vizinhos de Maior Grau</code>, os resultados não foram satisfatórios, mantendo o mesmo número de vacinas mesmo com uma alta taxa de distribuição diária. 
	</p>

	<p>
		Observando o comportamento de uma distribuição agressiva, nota-se a doença chega num determinado ponto em que ela quase se erradica, mas o mínimo número dos agentes infectados faz com que a doença volte a se proliferar e se estabilize num determinado valor. Dessa forma, essa estratégia de vacinação acaba não sendo viável, podendo ocorrer certos desperdícios de vacinas.
	</p>

	<p>
		Em relação ao período da doença na qual foi aplicada a inoculação, houve relevância em relação a distribuição <code class="code">Maior Grau</code>. Nota-se que para uma distribuição agressiva quando a doença já estiver estabilizada, os resultados são bastante satisfatórios. Precisou-se de apenas <code class="code">30%</code> de vacinados para que a doença se erradicasse - o menos número encontrado em todos os experimentos.
	</p>

	<p>
		Para as outras distribuições, não há diferença alguma em qual período da doença iniciar a inoculação. 
	</p>

	<p>
		Para finalizar, duas tabelas foram construídas com os dados obtidos acima e mais algumas simulações extras. As tabelas mostram um comparativo da quantidade do número de infectados em relação a quantidade de infectados no modelo padrão (<code class="code">4%</code>). Cada célula de dados da tabela possui esse valor (em baixo) e a taxa de vacinação diária (em cima). A primeira tabela são os dados obtidos nos testes de inoculação feitos na <ins>fase de dissipação</ins> da doença. A segunda tabela seria nos testes da <code><ins>fase de estabilização</ins></code>.
	</p>

	<h2>Tabela 1 - <ins>Inoculação na fase de dissipação</ins></h2>

	<table class="table table-bordered code">
	<col width="300">

	<tr class="header">
		<th style="visibility: hidden"></th>
		<th colspan="8">População Vacinada</th>
	</tr>

	<tr>
		<th style="visibility: hidden"></th>
		<th>
			10%
		</th>
		<th>
			20%
		</th>
		<th>
			30%
		</th>
		<th>
			40%
		</th>
		<th>
			50%
		</th>
		<th>
			60%
		</th>
		<th>
			70%
		</th>
		<th>
			80%
		</th>
	</tr>

	<!------------------- MARK 1 ------------------>

	<tr>
		<td rowspan="2" class="code">
			<dl class="dl-horizontal">
				<dt>
					Método: 
				</dt>
				<dd>
					<code>Aleatório</code>
				</dd>
				<dt>
					Período: 
				</dt>
				<dd>
					<mark>100 dias</mark>
				</dd>
			</dl>
		</td>
		<td>
			0.1%/d
		</td>
		<td>
			0.2%/d
		</td>
		<td>
			0.3%/d
		</td>
		<td>
			0.4%/d
		</td>
		<td>
			0.5%/d
		</td>
		<td>
			0.6%/d
		</td>
		<td>
			0.7%/d
		</td>
		<td>
			0.8%/d
		</td>
	</tr>
	<tr>
		<td>
			<code>80%</code>
		</td>
		<td>
			<code>70%</code>
		</td>
		<td>
			<code>60%</code>
		</td>
		<td>
			<code>50%</code>
		</td>
		<td>
			<code>40%</code>
		</td>
		<td>
			<code>30%</code>
		</td>
		<td>
			<code>20%</code>
		</td>
		<td>
			<mark>0%</mark>
		</td>
	</tr>


	<tr>
		<td rowspan="2" class="code">
			<dl class="dl-horizontal">
				<dt>
					Método: 
				</dt>
				<dd>
					<code>Maior Grau</code>
				</dd>
				<dt>
					Período: 
				</dt>
				<dd>
					<mark>100 dias</mark>
				</dd>
			</dl>
		</td>
		<td>
			0.1%/d
		</td>
		<td>
			0.2%/d
		</td>
		<td>
			0.3%/d
		</td>
		<td>
			0.4%/d
		</td>
		<td>
			0.5%/d
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>
	<tr>
		<td>
			<code>80%</code>
		</td>
		<td>
			<code>60%</code>
		</td>
		<td>
			<code>50%</code>
		</td>
		<td>
			<code>40%</code>
		</td>
		<td>
			<mark>0%</mark>
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>

	
	<tr>
		<td rowspan="2" class="code">
			<dl class="dl-horizontal">
				<dt>
					Método: 
				</dt>
				<dd>
					<code>Vizinhos de Maior Grau</code>
				</dd>
				<dt>
					Período: 
				</dt>
				<dd>
					<mark>100 dias</mark>
				</dd>
			</dl>
		</td>
		<td>
			0.1%/d
		</td>
		<td>
			0.2%/d
		</td>
		<td>
			0.3%/d
		</td>
		<td>
			0.4%/d
		</td>
		<td>
			0.5%/d
		</td>
		<td>
			0.6%/d
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>
	<tr>
		<td>
			<code>80%</code>
		</td>
		<td>
			<code>70%</code>
		</td>
		<td>
			<code>70%</code>
		</td>
		<td>
			<code>40%</code>
		</td>
		<td>
			<code>30%</code>
		</td>
		<td>
			<mark>0%</mark>
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>


	<!------------------- MARK 2 ------------------>
	
	<tr>
		<td rowspan="2" class="code">
			<dl class="dl-horizontal">
				<dt>
					Método: 
				</dt>
				<dd>
					<code>Aleatório</code>
				</dd>
				<dt>
					Período: 
				</dt>
				<dd>
					<mark>30 dias</mark>
				</dd>
			</dl>
		</td>
		<td>
			0.3%/d
		</td>
		<td>
			0.7%/d
		</td>
		<td>
			1.0%/d
		</td>
		<td>
			1.3%/d
		</td>
		<td>
			1.7%/d
		</td>
		<td>
			2.0%/d
		</td>
		<td>
			2.3%/d
		</td>
		<td>
			-
		</td>
	</tr>
	<tr>
		<td>
			<code>70%</code>
		</td>
		<td>
			<code>60%</code>
		</td>
		<td>
			<code>50%</code>
		</td>
		<td>
			<code>40%</code>
		</td>
		<td>
			<code>20%</code>
		</td>
		<td>
			<code>10%</code>
		</td>
		<td>
			<mark>0%</mark>
		</td>
		<td>
			-
		</td>
	</tr>

	
	<tr>
		<td rowspan="2" class="code">
			<dl class="dl-horizontal">
				<dt>
					Método: 
				</dt>
				<dd>
					<code>Maior Grau</code>
				</dd>
				<dt>
					Período: 
				</dt>
				<dd>
					<mark>30 dias</mark>
				</dd>
			</dl>
		</td>
		<td>
			0.3%/d
		</td>
		<td>
			0.7%/d
		</td>
		<td>
			1.0%/d
		</td>
		<td>
			1.3%/d
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>
	<tr>
		<td>
			<code>80%</code>
		</td>
		<td>
			<code>60%</code>
		</td>
		<td>
			<code>50%</code>
		</td>
		<td>
			<mark>0%</mark>
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>

	
	<tr>
		<td rowspan="2" class="code">
			<dl class="dl-horizontal">
				<dt>
					Método: 
				</dt>
				<dd>
					<code>Vizinhos de Maior Grau</code>
				</dd>
				<dt>
					Período: 
				</dt>
				<dd>
					<mark>30 dias</mark>
				</dd>
			</dl>
		</td>
		<td>
			0.3%/d
		</td>
		<td>
			0.7%/d
		</td>
		<td>
			1.0%/d
		</td>
		<td>
			1.3%/d
		</td>
		<td>
			1.7%/d
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>
	<tr>
		<td>
			<code>80%</code>
		</td>
		<td>
			<code>60%</code>
		</td>
		<td>
			<code>50%</code>
		</td>
		<td>
			<code>40%</code>
		</td>
		<td>
			<mark>0%</mark>
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>


	<!------------------- MARK 3 ------------------>

	<tr>
		<td rowspan="2" class="code">
			<dl class="dl-horizontal">
				<dt>
					Método: 
				</dt>
				<dd>
					<code>Aleatório</code>
				</dd>
				<dt>
					Período: 
				</dt>
				<dd>
					<mark>7 dias</mark>
				</dd>
			</dl>
		</td>
		<td>
			1.4%/d
		</td>
		<td>
			2.9%/d
		</td>
		<td>
			4.3%/d
		</td>
		<td>
			5.7%/d
		</td>
		<td>
			7.1%/d
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>
	<tr>
		<td>
			<code>90%</code>
		</td>

		<td>
			<code>70%</code>
		</td>
		<td>
			<code>60%</code>
		</td>
		<td>
			<code>30%</code>
		</td>
		<td>
			<mark>0%</mark>
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>
	

	<tr>
		<td rowspan="2" class="code">
			<dl class="dl-horizontal">
				<dt>
					Método: 
				</dt>
				<dd>
					<code>Maior Grau</code>
				</dd>
				<dt>
					Período: 
				</dt>
				<dd>
					<mark>7 dias</mark>
				</dd>
			</dl>
		</td>
		<td>
			1.4%/d
		</td>
		<td>
			2.9%/d
		</td>
		<td>
			4.3%/d
		</td>
		<td>
			5.7%/d
		</td>
		<td>
			7.1%/d
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>
	<tr>
		<td>
			<code>80%</code>
		</td>
		<td>
			<code>70%</code>
		</td>
		<td>
			<code>60%</code>
		</td>
		<td>
			<code>40%</code>
		</td>
		<td>
			<mark>0%</mark>
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>

	
	<tr>
		<td rowspan="2" class="code">
			<dl class="dl-horizontal">
				<dt>
					Método: 
				</dt>
				<dd>
					<code>Vizinhos de Maior Grau</code>
				</dd>
				<dt>
					Período: 
				</dt>
				<dd>
					<mark>7 dias</mark>
				</dd>
			</dl>
		</td>
		<td>
			1.4%/d
		</td>
		<td>
			2.9%/d
		</td>
		<td>
			4.3%/d
		</td>
		<td>
			5.7%/d
		</td>
		<td>
			7.1%/d
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>
	<tr>
		<td>
			<code>70%</code>
		</td>
		<td>
			<code>70%</code>
		</td>
		<td>
			<code>50%</code>
		</td>
		<td>
			<code>40%</code>
		</td>
		<td>
			<mark>0%</mark>
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>


	</table>

	<h2>Tabela 2 - <code><ins>Inoculação na fase de estabilização</ins></code></h2>

	<table class="table table-bordered">
	<col width="300">

	<tr class="header">
		<th style="visibility: hidden"></th>
		<th colspan="8">População Vacinada</th>
	</tr>

	<tr>
		<th style="visibility: hidden"></th>
		<th>
			10%
		</th>
		<th>
			20%
		</th>
		<th>
			30%
		</th>
		<th>
			40%
		</th>
		<th>
			50%
		</th>
		<th>
			60%
		</th>
		<th>
			70%
		</th>
		<th>
			80%
		</th>
	</tr>

	<!------------------- MARK 4 ------------------>

	<tr>
		<td rowspan="2" class="code">
			<dl class="dl-horizontal">
				<dt>
					Método: 
				</dt>
				<dd>
					<code>Aleatório</code>
				</dd>
				<dt>
					Período: 
				</dt>
				<dd>
					<mark>100 dias</mark>
				</dd>
			</dl>
		</td>
		<td>
			0.1%/d
		</td>
		<td>
			0.2%/d
		</td>
		<td>
			0.3%/d
		</td>
		<td>
			0.4%/d
		</td>
		<td>
			0.5%/d
		</td>
		<td>
			0.6%/d
		</td>
		<td>
			0.7%/d
		</td>
		<td>
			0.8%/d
		</td>
	</tr>
	<tr>
		<td>
			<code>80%</code>
		</td>
		<td>
			<code>70%</code>
		</td>
		<td>
			<code>60%</code>
		</td>
		<td>
			<code>40%</code>
		</td>
		<td>
			<code>30%</code>
		</td>
		<td>
			<code>20%</code>
		</td>
		<td>
			<code>10%</code>
		</td>
		<td>
			<mark>0%</mark>
		</td>
	</tr>

	
	<tr>
		<td rowspan="2" class="code">
			<dl class="dl-horizontal">
				<dt>
					Método: 
				</dt>
				<dd>
					<code>Maior Grau</code>
				</dd>
				<dt>
					Período: 
				</dt>
				<dd>
					<mark>100 dias</mark>
				</dd>
			</dl>
		</td>
		<td>
			0.1%/d
		</td>
		<td>
			0.2%/d
		</td>
		<td>
			0.3%/d
		</td>
		<td>
			0.4%/d
		</td>
		<td>
			0.5%/d
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>
	<tr>
		<td>
			<code>90%</code>
		</td>
		<td>
			<code>70%</code>
		</td>
		<td>
			<code>50%</code>
		</td>
		<td>
			<code>30%</code>
		</td>
		<td>
			<mark>0%</mark>
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>

	
	<tr>
		<td rowspan="2" class="code">
			<dl class="dl-horizontal">
				<dt>
					Método: 
				</dt>
				<dd>
					<code>Vizinhos de Maior Grau</code>
				</dd>
				<dt>
					Período: 
				</dt>
				<dd>
					<mark>100 dias</mark>
				</dd>
			</dl>
		</td>
		<td>
			0.1%/d
		</td>
		<td>
			0.2%/d
		</td>
		<td>
			0.3%/d
		</td>
		<td>
			0.4%/d
		</td>
		<td>
			0.5%/d
		</td>
		<td>
			0.6%/d
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>
	<tr>
		<td>
			<code>80%</code>
		</td>
		<td>
			<code>60%</code>
		</td>
		<td>
			<code>50%</code>
		</td>
		<td>
			<code>40%</code>
		</td>
		<td>
			<code>30%</code>
		</td>
		<td>
			<mark>0%</mark>
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>


	<!------------------- MARK 5 ------------------>
	
	<tr>
		<td rowspan="2" class="code">
			<dl class="dl-horizontal">
				<dt>
					Método: 
				</dt>
				<dd>
					<code>Aleatório</code>
				</dd>
				<dt>
					Período: 
				</dt>
				<dd>
					<mark>30 dias</mark>
				</dd>
			</dl>
		</td>
		<td>
			0.3%/d
		</td>
		<td>
			0.7%/d
		</td>
		<td>
			1.0%/d
		</td>
		<td>
			1.3%/d
		</td>
		<td>
			1.7%/d
		</td>
		<td>
			2.0%/d
		</td>
		<td>
			2.3%/d
		</td>
		<td>
			-
		</td>
	</tr>
	<tr>
		<td>
			<code>70%</code>
		</td>
		<td>
			<code>70%</code>
		</td>
		<td>
			<code>60%</code>
		</td>
		<td>
			<code>50%</code>
		</td>
		<td>
			<code>40%</code>
		</td>
		<td>
			<code>20%</code>
		</td>
		<td>
			<mark>0%</mark>
		</td>
		<td>
			-
		</td>
	</tr>

	
	<tr>
		<td rowspan="2" class="code">
			<dl class="dl-horizontal">
				<dt>
					Método: 
				</dt>
				<dd>
					<code>Maior Grau</code>
				</dd>
				<dt>
					Período: 
				</dt>
				<dd>
					<mark>30 dias</mark>
				</dd>
			</dl>
		</td>
		<td>
			0.3%/d
		</td>
		<td>
			0.7%/d
		</td>
		<td>
			1.0%/d
		</td>
		<td>
			1.3%/d
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>
	<tr>
		<td>
			<code>80%</code>
		</td>
		<td>
			<code>60%</code>
		</td>
		<td>
			<code>50%</code>
		</td>
		<td>
			<mark>0%</mark>
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>

	
	<tr>
		<td rowspan="2" class="code">
			<dl class="dl-horizontal">
				<dt>
					Método: 
				</dt>
				<dd>
					<code>Vizinhos de Maior Grau</code>
				</dd>
				<dt>
					Período: 
				</dt>
				<dd>
					<mark>30 dias</mark>
				</dd>
			</dl>
		</td>
		<td>
			0.3%/d
		</td>
		<td>
			0.7%/d
		</td>
		<td>
			1.0%/d
		</td>
		<td>
			1.3%/d
		</td>
		<td>
			1.7%/d
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>
	<tr>
		<td>
			<code>80%</code>
		</td>
		<td>
			<code>60%</code>
		</td>
		<td>
			<code>40%</code>
		</td>
		<td>
			<code>40%</code>
		</td>
		<td>
			<mark>0%</mark>
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>


	<!------------------- MARK 6 ------------------>

	<tr>
		<td rowspan="2" class="code">
			<dl class="dl-horizontal">
				<dt>
					Método: 
				</dt>
				<dd>
					<code>Aleatório</code>
				</dd>
				<dt>
					Período: 
				</dt>
				<dd>
					<mark>7 dias</mark>
				</dd>
			</dl>
		</td>
		<td>
			1.4%/d
		</td>
		<td>
			2.9%/d
		</td>
		<td>
			4.3%/d
		</td>
		<td>
			5.7%/d
		</td>
		<td>
			7.1%/d
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>
	<tr>
		<td>
			<code>70%</code>
		</td>
		<td>
			<code>60%</code>
		</td>
		<td>
			<code>50%</code>
		</td>
		<td>
			<code>40%</code>
		</td>
		<td>
			<mark>0%</mark>
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>

	
	<tr>
		<td rowspan="2" class="code">
			<dl class="dl-horizontal">
				<dt>
					Método: 
				</dt>
				<dd>
					<code>Maior Grau</code>
				</dd>
				<dt>
					Período: 
				</dt>
				<dd>
					<mark>7 dias</mark>
				</dd>
			</dl>
		</td>
		<td>
			1.4%/d
		</td>
		<td>
			2.9%/d
		</td>
		<td>
			4.3%/d
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>
	<tr>
		<td>
			<code>70%</code>
		</td>
		<td>
			<code>60%</code></mark>
		</td>
		<td>
			<mark>0%</mark>
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>

	
	<tr>
		<td rowspan="2" class="code">
			<dl class="dl-horizontal">
				<dt>
					Método: 
				</dt>
				<dd>
					<code>Vizinhos de Maior Grau</code>
				</dd>
				<dt>
					Período: 
				</dt>
				<dd>
					<mark>7 dias</mark>
				</dd>
			</dl>
		</td>
		<td>
			1.4%/d
		</td>
		<td>
			2.9%/d
		</td>
		<td>
			4.3%/d
		</td>
		<td>
			5.7%/d
		</td>
		<td>
			7.1%/d
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>
	<tr>
		<td>
			<code>70%</code>
		</td>
		<td>
			<code>70%</code>
		</td>
		<td>
			<code>70%</code>
		</td>
		<td>
			<code>50%</code>
		</td>
		<td>
			<mark>0%</mark>
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
		<td>
			-
		</td>
	</tr>


	</table>

	<h2>Conclusão 2</h2>

	<p>
		Na terceira parte do experimento, foi analizado o comportamento da doença em diferentes cenários de distribuições de vacinas. Cada cenário era caracterizado pelo método de distribuição, a taxa diária de distribuição, o número relacional de vacinados e o período da doença na qual a distribuição era iniciada.
	</p>

	<p>
		As taxas de distribuições diárias de vacinas podem ser classificadas como baixas, moderadas e altas. Foi observado melhor eficiência na dissipação da doença taxas moderadas de distribuições. Considerando o passo de proliferação adotado nos experimentos (passo diário), a melhor taxa moderada encontrada foi de <code class="code">1.3%</code>. Segundo os testes, esta seria a taxa recomendada para uma doença em <ins>fase de proliferação</ins>, na qual necessitaria priorizar a vacinação de indivíduos com o maior número de contatos infectados. Dessa forma, com apenas <code class="code">40%</code> de vacinados na população, a doença deixaria de existir.
	</p>

	<p>
		A taxa moderada de distribuição é a mais indicada para a <ins>fase de proliferação</ins> da doença. Apesar dos testes mostrarem que altas taxas não são relevantes para a maioria dos casos, há uma excessão. Existe apenas uma estratégia na qual as altas taxas se torna relevantes. Para este caso, só seria possível utilizar na <code><ins>fase de estabilização</ins></code> da doença. Com uma taxa de <code class="code">4.3%</code> - iniciando com a doença já estável, é possível erradicá-la desde que as vacinas sejam priorizadas à indivídos com o maior número de contatos infectados. Dessa forma, necessitaria de apenas <code class="code">30%</code> de vacinados na população para matar a doença.
	</p>

	<p>
		Em suma, a melhor distribuição de vacina testada é a <code class="code">Maior Grau</code>. Se a doença estiver em seu início, a melhor forma de combatê-la é descobrindo o passo na qual a doença se prolifera, distribuindo <code class="code">1.3%</code> vacinas por passo até atingir <code class="code">40%</code> da população vacinada. Se a doença já estiver um bom tempo na população, será melhor vacinar <code class="code">30%</code> da mesma, distribuindo <code class="code">4.3%</code> vacinas por passo.
	</p>

	<h2>Conclusão Final</h2>

	<p>
		Foi analizado dois tipos de testes com o objetivo de erradicar doenças: através das características da mesma e através da distribuições de vacinas. As dezenas de simulações feitas durante a pesquisa comprovam que existem estratégias que são eficazes e outras não. Foi constado que existe um método de distribuição de vacinas que realmente trabalha melhor contra as doenças, e aplicando-a num ritmo correto e na fase da doença correta, é possível matar a doença com o mínimo de vacinas possíveis. 
	</p>

	<p>
		Para complementar as distribuições, ainda é possível que as mesmas sejam mais eficiêntes quando a população está consciênte em relação à práticas saudáveis em suas vidas, fazendo com que o agente infeccioso tenha maior dificuldade de se proliferar contra indivíduos com um alto sistema imunológico.
	</p>

</div>

</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</html>