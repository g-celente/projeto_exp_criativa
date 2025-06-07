# Configuração do Sistema de Notificações por Email

## 1. Configuração do Email (Gmail)

1. Acesse sua conta Google
2. Ative a verificação em duas etapas em https://myaccount.google.com/security
3. Gere uma senha de app:
   - Vá para https://myaccount.google.com/security
   - Procure por "Senhas de app"
   - Selecione "Outro" e dê um nome (ex: MoneyTrack)
   - Copie a senha gerada

4. Configure o arquivo NotificationService.php:
   - Abra `app/service/NotificationService.php`
   - Substitua 'seu-email@gmail.com' pelo seu email Gmail
   - Substitua 'sua-senha-de-app' pela senha de app gerada

## 2. Configuração do Agendador de Tarefas do Windows

1. Abra o Windows Task Scheduler:
   - Pressione Win + R
   - Digite "taskschd.msc"
   - Pressione Enter

2. Crie uma nova tarefa:
   - Clique com botão direito em "Biblioteca do Agendador de Tarefas"
   - Selecione "Criar Tarefa Básica"

3. Configure a tarefa:
   - Nome: "MoneyTrack - Verificação de Notificações"
   - Descrição: "Verifica e envia notificações de lembretes financeiros"
   - Disparador: Diariamente às 8:00
   - Ação: Iniciar um programa
   - Programa/script: `C:\xampp\htdocs\teste\crons\check_notifications.bat`

4. Configurações adicionais:
   - Na aba "Condições":
     - Desmarque "Iniciar a tarefa somente se o computador estiver ocioso..."
     - Desmarque "Parar se o computador começar a usar bateria"
   - Na aba "Configurações":
     - Marque "Executar a tarefa o mais cedo possível..."
     - Marque "Se a tarefa falhar, reiniciar a cada: 5 minutos"
     - Configure "Tentar reiniciar até: 3 vezes"

## 3. Testando o Sistema

1. Execute o script manualmente para testar:
   ```powershell
   cd C:\xampp\htdocs\teste\crons
   .\check_notifications.bat
   ```

2. Verifique o arquivo de log:
   - Abra `C:\xampp\htdocs\teste\crons\notification_cron.log`
   - Procure por mensagens de sucesso ou erro

## 4. Solução de Problemas

Se os emails não estiverem sendo enviados:

1. Verifique as credenciais do Gmail:
   - Confirme se o email está correto
   - Verifique se a senha de app está correta
   - Teste criar uma nova senha de app

2. Verifique o log de erros:
   - Abra o arquivo notification_cron.log
   - Procure por mensagens de erro específicas

3. Verifique o Agendador de Tarefas:
   - Abra o histórico da tarefa
   - Verifique se está sendo executada
   - Confirme se o caminho do script está correto

4. Teste o PHP:
   ```powershell
   cd C:\xampp\htdocs\teste
   php -v
   php crons/verifica_vencimentos.php
   ```

## 5. Preferências de Notificação

Os usuários podem configurar suas preferências em:
http://localhost/teste/view/notification-settings/NotificationSettingsView.php

Opções disponíveis:
- Ativar/desativar notificações
- Escolher dias de antecedência (1-3 dias)
- Tipo de notificação (apenas vencimentos ou todos os lembretes)
