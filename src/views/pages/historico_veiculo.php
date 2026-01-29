<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico do Veículo - Sistema Oficina</title>
    <link rel="stylesheet" href="/assets/css/historico.css">
</head>

<body>
    <div class="container">
        <!-- Cabeçalho -->
        <div class="header">
            <div class="header-content">
                <div class="vehicle-info">
                    <h1>Volkswagen Gol 1.6 Total Flex</h1>
                    <div class="vehicle-details">
                        <div class="detail-item">
                            <span class="detail-label">Placa</span>
                            <span class="detail-value">ABC-1234</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Ano</span>
                            <span class="detail-value">2018/2019</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Cor</span>
                            <span class="detail-value">Prata</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Proprietário</span>
                            <span class="detail-value">João da Silva</span>
                        </div>
                    </div>
                </div>
                <a href="/veiculos" class="btn-voltar">← Voltar</a>
            </div>
        </div>

        <!-- Conteúdo -->
        <div class="content">
            <!-- Estatísticas -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">12</div>
                    <div class="stat-label">Total de Visitas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">R$ 8.540,00</div>
                    <div class="stat-label">Valor Total Gasto</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">68.450 km</div>
                    <div class="stat-label">Quilometragem Atual</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">15/03/2025</div>
                    <div class="stat-label">Última Visita</div>
                </div>
            </div>

            <!-- Timeline de Histórico -->
            <h2 class="section-title">Histórico de Serviços</h2>
            <div class="timeline">

                <!-- Item 1 -->
                <div class="timeline-item">
                    <div class="timeline-header">
                        <span class="timeline-date">15/03/2025 - 10:30</span>
                        <span class="timeline-type type-manutencao">Manutenção</span>
                    </div>
                    <div class="timeline-content">
                        <h3>Troca de Óleo e Filtros <span class="status-badge status-concluido">Concluído</span></h3>
                        <ul class="servicos-list">
                            <li>Troca de óleo sintético 5W30</li>
                            <li>Substituição do filtro de óleo</li>
                            <li>Substituição do filtro de ar</li>
                            <li>Inspeção geral do motor</li>
                        </ul>
                        <div class="timeline-details">
                            <div class="detail-box">
                                <strong>Quilometragem:</strong>
                                <span>68.450 km</span>
                            </div>
                            <div class="detail-box">
                                <strong>Mecânico:</strong>
                                <span>Carlos Mendes</span>
                            </div>
                            <div class="detail-box">
                                <strong>Valor:</strong>
                                <span>R$ 380,00</span>
                            </div>
                            <div class="detail-box">
                                <strong>Garantia:</strong>
                                <span>6 meses</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="timeline-item">
                    <div class="timeline-header">
                        <span class="timeline-date">28/01/2025 - 14:15</span>
                        <span class="timeline-type type-reparo">Reparo</span>
                    </div>
                    <div class="timeline-content">
                        <h3>Reparo no Sistema de Freios <span class="status-badge status-concluido">Concluído</span></h3>
                        <ul class="servicos-list">
                            <li>Troca das pastilhas de freio dianteiras</li>
                            <li>Retífica dos discos de freio</li>
                            <li>Sangria do sistema de freios</li>
                            <li>Teste de frenagem</li>
                        </ul>
                        <div class="timeline-details">
                            <div class="detail-box">
                                <strong>Quilometragem:</strong>
                                <span>67.820 km</span>
                            </div>
                            <div class="detail-box">
                                <strong>Mecânico:</strong>
                                <span>Roberto Lima</span>
                            </div>
                            <div class="detail-box">
                                <strong>Valor:</strong>
                                <span>R$ 850,00</span>
                            </div>
                            <div class="detail-box">
                                <strong>Garantia:</strong>
                                <span>12 meses</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Item 3 -->
                <div class="timeline-item">
                    <div class="timeline-header">
                        <span class="timeline-date">10/12/2024 - 09:00</span>
                        <span class="timeline-type type-revisao">Revisão</span>
                    </div>
                    <div class="timeline-content">
                        <h3>Revisão dos 60.000 km <span class="status-badge status-concluido">Concluído</span></h3>
                        <ul class="servicos-list">
                            <li>Troca de correia dentada</li>
                            <li>Troca de velas de ignição</li>
                            <li>Limpeza de bicos injetores</li>
                            <li>Alinhamento e balanceamento</li>
                            <li>Revisão geral do sistema elétrico</li>
                            <li>Verificação de suspensão e amortecedores</li>
                        </ul>
                        <div class="timeline-details">
                            <div class="detail-box">
                                <strong>Quilometragem:</strong>
                                <span>66.890 km</span>
                            </div>
                            <div class="detail-box">
                                <strong>Mecânico:</strong>
                                <span>Carlos Mendes</span>
                            </div>
                            <div class="detail-box">
                                <strong>Valor:</strong>
                                <span>R$ 1.850,00</span>
                            </div>
                            <div class="detail-box">
                                <strong>Garantia:</strong>
                                <span>12 meses</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Item 4 -->
                <div class="timeline-item">
                    <div class="timeline-header">
                        <span class="timeline-date">05/10/2024 - 15:45</span>
                        <span class="timeline-type type-reparo">Reparo</span>
                    </div>
                    <div class="timeline-content">
                        <h3>Troca de Bateria <span class="status-badge status-concluido">Concluído</span></h3>
                        <ul class="servicos-list">
                            <li>Remoção da bateria antiga</li>
                            <li>Instalação de bateria 60Ah</li>
                            <li>Teste do sistema de carga</li>
                            <li>Limpeza dos terminais</li>
                        </ul>
                        <div class="timeline-details">
                            <div class="detail-box">
                                <strong>Quilometragem:</strong>
                                <span>65.120 km</span>
                            </div>
                            <div class="detail-box">
                                <strong>Mecânico:</strong>
                                <span>Pedro Santos</span>
                            </div>
                            <div class="detail-box">
                                <strong>Valor:</strong>
                                <span>R$ 420,00</span>
                            </div>
                            <div class="detail-box">
                                <strong>Garantia:</strong>
                                <span>18 meses</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Item 5 -->
                <div class="timeline-item">
                    <div class="timeline-header">
                        <span class="timeline-date">22/08/2024 - 11:20</span>
                        <span class="timeline-type type-diagnostico">Diagnóstico</span>
                    </div>
                    <div class="timeline-content">
                        <h3>Diagnóstico Eletrônico Completo <span class="status-badge status-concluido">Concluído</span></h3>
                        <ul class="servicos-list">
                            <li>Escaneamento de módulos eletrônicos</li>
                            <li>Leitura de códigos de falha</li>
                            <li>Teste de sensores</li>
                            <li>Relatório técnico detalhado</li>
                        </ul>
                        <div class="timeline-details">
                            <div class="detail-box">
                                <strong>Quilometragem:</strong>
                                <span>63.580 km</span>
                            </div>
                            <div class="detail-box">
                                <strong>Mecânico:</strong>
                                <span>André Costa</span>
                            </div>
                            <div class="detail-box">
                                <strong>Valor:</strong>
                                <span>R$ 150,00</span>
                            </div>
                            <div class="detail-box">
                                <strong>Resultado:</strong>
                                <span>Sem falhas</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Item 6 -->
                <div class="timeline-item">
                    <div class="timeline-header">
                        <span class="timeline-date">15/06/2024 - 08:30</span>
                        <span class="timeline-type type-manutencao">Manutenção</span>
                    </div>
                    <div class="timeline-content">
                        <h3>Troca de Pneus <span class="status-badge status-concluido">Concluído</span></h3>
                        <ul class="servicos-list">
                            <li>Substituição de 4 pneus Pirelli 175/70 R14</li>
                            <li>Balanceamento das 4 rodas</li>
                            <li>Alinhamento de direção</li>
                            <li>Calibragem dos pneus</li>
                        </ul>
                        <div class="timeline-details">
                            <div class="detail-box">
                                <strong>Quilometragem:</strong>
                                <span>61.900 km</span>
                            </div>
                            <div class="detail-box">
                                <strong>Mecânico:</strong>
                                <span>Roberto Lima</span>
                            </div>
                            <div class="detail-box">
                                <strong>Valor:</strong>
                                <span>R$ 1.680,00</span>
                            </div>
                            <div class="detail-box">
                                <strong>Garantia:</strong>
                                <span>24 meses</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Item 7 -->
                <div class="timeline-item">
                    <div class="timeline-header">
                        <span class="timeline-date">03/04/2024 - 13:00</span>
                        <span class="timeline-type type-reparo">Reparo</span>
                    </div>
                    <div class="timeline-content">
                        <h3>Reparo no Sistema de Ar Condicionado <span class="status-badge status-concluido">Concluído</span></h3>
                        <ul class="servicos-list">
                            <li>Recarga de gás refrigerante</li>
                            <li>Troca do filtro de cabine</li>
                            <li>Limpeza do evaporador</li>
                            <li>Teste de temperatura</li>
                        </ul>
                        <div class="timeline-details">
                            <div class="detail-box">
                                <strong>Quilometragem:</strong>
                                <span>59.450 km</span>
                            </div>
                            <div class="detail-box">
                                <strong>Mecânico:</strong>
                                <span>Carlos Mendes</span>
                            </div>
                            <div class="detail-box">
                                <strong>Valor:</strong>
                                <span>R$ 520,00</span>
                            </div>
                            <div class="detail-box">
                                <strong>Garantia:</strong>
                                <span>6 meses</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Item 8 -->
                <div class="timeline-item">
                    <div class="timeline-header">
                        <span class="timeline-date">18/02/2024 - 10:15</span>
                        <span class="timeline-type type-manutencao">Manutenção</span>
                    </div>
                    <div class="timeline-content">
                        <h3>Manutenção Preventiva <span class="status-badge status-concluido">Concluído</span></h3>
                        <ul class="servicos-list">
                            <li>Troca de óleo e filtros</li>
                            <li>Revisão do sistema de arrefecimento</li>
                            <li>Verificação de correias e mangueiras</li>
                            <li>Inspeção de freios e suspensão</li>
                        </ul>
                        <div class="timeline-details">
                            <div class="detail-box">
                                <strong>Quilometragem:</strong>
                                <span>57.200 km</span>
                            </div>
                            <div class="detail-box">
                                <strong>Mecânico:</strong>
                                <span>Pedro Santos</span>
                            </div>
                            <div class="detail-box">
                                <strong>Valor:</strong>
                                <span>R$ 680,00</span>
                            </div>
                            <div class="detail-box">
                                <strong>Garantia:</strong>
                                <span>6 meses</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>