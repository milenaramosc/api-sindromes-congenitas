<?php

#--------------------------------------------------------------------
# Exemplo de arquivo de env
#
# Copie e renomeie este arquivo para .env.php e adicione as
# configurações necessárias.
#--------------------------------------------------------------------

#--------------------------------------------------------------------
# AMBIENTE EM EXECUÇÃO
#--------------------------------------------------------------------
# dev | homolog | prod
define('AMBIENTE', 'dev');

#--------------------------------------------------------------------
# CONFIGURAÇÕES PADRÃO PARA SMS E DESCRIÇÃO PIX
#--------------------------------------------------------------------
define('NOME_INSTITUICAO', 'UFJF');

#--------------------------------------------------------------------
# CPF/CNPJ da empresa (cliente RBM)
# STRING COM 11 OU 14 CARACTERES
#--------------------------------------------------------------------
define('CPFCNPJ_EMPRESA', '');

#--------------------------------------------------------------------
# ID PARA ENVIOS DE SMS
#--------------------------------------------------------------------
define('ID_SMS', '');

#--------------------------------------------------------------------
# BANCO DE DADOS PROJETO
#--------------------------------------------------------------------
define('DB_HOST', '');
define('DB_NAME', '');
define('DB_USER', '');
define('DB_PASS', '');


#--------------------------------------------------------------------
# Customização de cores
#--------------------------------------------------------------------
define('COR_BASE_TITULO', '747474');
define('COR_BASE_LINHA', '909194');
define('COR_FONT_CABECALHO', 'ffffff');
define('COR_FONT_LINHA', 'ffffff');

#--------------------------------------------------------------------
# Timezone padrão do sistema
# Utilizando America/Fortaleza pois não há horário de versão
#--------------------------------------------------------------------
define('DEFAULT_TIME_ZONE', 'America/Fortaleza');

#--------------------------------------------------------------------
# CONFIGURAÇÕES PARA ENVIO DE E-MAIL
#--------------------------------------------------------------------
define('EMAIL_USERNAME', '');
define('EMAIL_PASSWORD', '');

#--------------------------------------------------------------------
# ACESSO BIO
#
# Define se o usuário passa por verificação do
# Acesso Bio no cadastro
#--------------------------------------------------------------------
define('ACESSOBIO', false);

#--------------------------------------------------------------------
# CRIPTOGRAFIA DE SENHAS
#
# Deve possuir 32 caracteres
# Pode ser gerado pelo comando PHP: echo bin2hex(random_bytes(16));
# Devemos gerar chaves diferentes entre os 
# diferentes ambientes de execução
#--------------------------------------------------------------------
define('USER_PASSWORD_KEY', '');
define('ADMIN_PASSWORD_KEY', '');
define('THIRDPARTY_PASSWORD_KEY', '');
define('APIADMIN_PASSWORD_KEY', '');
define('BANK_ACCOUNT_PASSWORD_KEY', '');

#--------------------------------------------------------------------
# CHAVE PARA HANDSHAKE DA BMP
#--------------------------------------------------------------------
define('BMP_SECRET_KEY', '');

#--------------------------------------------------------------------
# FIM CONFIGURAÇÕES PADRÃO DAS API'S
#--------------------------------------------------------------------
