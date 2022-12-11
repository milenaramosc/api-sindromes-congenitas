# Padronização dos retornos de mensagens do sistema

## Exemplos de código

- E011-000
- S023-293
- D999-999

## Padrão de código de mensagem

Utilize a base hexadecimal (de 0 a F) - para aumentar o range de representação de códigos de mensagem.
Os arquivos na pasta 'mensagens' contém as mensagens. Podem ser utilizados para consulta ou adição de novas mensagens.

- O primeiro caractere se refere ao tipo de mensagem.
    Erro    -> E,
    Sucesso -> S,
    Padrão  -> D.

- Os dois seguintes serão um identificador do módulo que o arquivo se refere. Por exemplo, usuários, conta, cartão, etc...

- O caractere seguinte identifica o tipo de arquivo:
    Model      -> 1,
    View       -> 2,
    Controller -> 3.

- O sinal '-' deve ser adicionado

- Os três últimos dígitos identificam a mensagem de forma única

À princípio, iremos tomar os controllers como base para identificador dos módulos:
    - 01: Analise
    - 02: Auth
    - 03: Auth_desvinculacao
    - 04: Bancos
    - 05: Boleto
    - 06: Cancelamento
    - 07: Cartao
    - 08: Chamados
    - 09: Cielo
    - 0A: Cliente
    - 0B: ClientePagPlay
    - 0C: Coban
    - 0D: Cobranca
    - 0E: Configuracoes
    - 0F: ContaBancaria
    - 10: Conta
    - 11: Contatos
    - 12: Emprestimo
    - 13: Extrato
    - 14: FichaGrafica
    - 15: Firebase
    - 16: Graficos
    - 17: Indicacoes
    - 18: LogAcesso
    - 19: LogUsuario
    - 1A: Notificacoes
    - 1B: Pagamento
    - 1C: ParcelasAntecipacao
    - 1D: Pix
    - 1E: RamoAtividade
    - 1F: Recargas
    - 20: Remessas
    - 21: Saque
    - 22: ScoreUsuario
    - 23: Services
    - 24: Sms
    - 25: Solicitacao
    - 26: StatusTransacao
    - 27: TaxaUsuario
    - 28: TipoTaxa
    - 29: Transacao
    - 2A: Usuario
    - 2B: Vendas
    - 2C: Vendedor
    - 2D: BancosParceiros
    - 2E: ServicosDigitais
    - 2F: TelenetSaidaController
    - 30: Middleware
    - 31: Lead
    - 32: Empresa
    - 33: TransferenciaPix
    - 34: TransferenciaTed
    - 35: Transferencia
    - 36: LogConsultaChavePix
    - 37: LogPagamentoPix
    - 38: ApiAdmin
    - 39: LogReader
    - 40: LogAcesso
    - 41: CartaoBacen
    - 42: CartaoBacenSegundaVia
    - 43: PixChavesCriadas
    - 44: TokensMicroservicos
    - 45: RUP
    - 46: Celcoin
    - 47: ContaDigital
    - 48: favorecido
    - 49: firebase
    - 4A: vendaPdv
    - 4B: services
    - 4C: representanteLegal
    - 4D: contaBacen
    - 4E: servicesBmpAgenciaPadrao
    - 4F: usuariosDocumentos
    - 50: saldo
    - 51: rupTipoAnexos
    - 52: rupDocs
    - 53: imageReader
    - 54: taxaCoban
    - 55: taxaUsuario
    - 56: listarClientes
    - 57: listarContas
    - 58: contaBacenLogStatus
    - 59: listarDocumentos
    - 5A: transferenciaP2P
    - 5B: contatosP2P
    - 5C: bmpHandshake
    - 5D: contaBacenMae
    - 5E: transacaoSpread
    - 5F: depositoBoleto
    - 60: cedente
    - 61: carteiraBmp
    - 62: email
    - 63: saldoGeral
    - 64: transacoesDashboard
    - 65: pagamentoBoleto
    - 66: transferenciaTed
    - 67: telasAcesso
    - 68: pushNotification
