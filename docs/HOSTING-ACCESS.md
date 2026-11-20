# Доступ к хостингу Educado

Проверено 2026-11-06 по открытой панели cPanel. Секреты в этом файле не хранятся.

## Подключение

| Параметр | Значение |
| --- | --- |
| Провайдер | HOSTiQ, виртуальный хостинг HQ10 |
| Кабинет | https://hostiq.ua/clients/clientarea.php |
| Услуга | https://hostiq.ua/clients/clientarea.php?action=productdetails&id=170407 |
| cPanel | https://eushared26.twinservers.net:2083/ |
| SSH/SFTP сервер | eushared26.twinservers.net |
| IP из cPanel | 193.111.63.15 |
| SSH/SFTP порт | 21098 |
| Пользователь | educadoc |
| Домашний каталог | /home/educadoc |
| Каталог educado.school | /home/educadoc/educado.school |
| Каталог educado.com.ua | /home/educadoc/public_html |

Это разные каталоги на одном аккаунте. Для текущего проекта использовать каталог `educado.school`.
Порт указан по [официальной инструкции HOSTiQ](https://hostiq.ua/wiki/ssh-connect/).

## Ключ пользователя на этом компьютере

- Приватный ключ: `C:\Users\Serhii\.ssh\id_ed25519` — не копировать в проект или на хостинг.
- Публичный ключ: `C:\Users\Serhii\.ssh\id_ed25519.pub`.
- Отпечаток ED25519: `SHA256:/sZZM66oLho11t1Fuyfw8nzw0I6asq+hA8JMsaDHb4I`.
- Имя импортированного публичного ключа в cPanel: `serhii_local_ed25519`.
- Статус: **authorized**, подтверждено в cPanel 2026-11-06. Пользователь разрешил авторизацию.
- Существующий `krasprod` принадлежит не пользователю по его уточнению; он не удалён и не изменён.

Подключение (ввести парольную фразу ключа локально при запросе):

```powershell
ssh -p 21098 -i C:\Users\Serhii\.ssh\id_ed25519 -o IdentitiesOnly=yes educadoc@eushared26.twinservers.net
```

Для SFTP использовать те же сервер, порт, пользователя и приватный ключ.
Полный SSH-вход по ключу проверен 2026-11-06 после интерактивного ввода предоставленной пользователем парольной фразы. `whoami` вернул `educadoc`, `pwd` — `/home/educadoc`, `ls -ld /home/educadoc/educado.school` подтвердил существование каталога и владельца `educadoc`. Сессия закрыта после проверки; сайт и база не изменялись.
Приватный ключ зашифрован. Для последующих подключений вводить парольную фразу локально или использовать свой SSH-агент; в проекте она не хранится.
Пароль cPanel не извлекался; открытый кабинет HOSTiQ позволяет переходить в cPanel.

## Управление

В cPanel: **Доступ по SSH → Управление ключами SSH → serhii_local_ed25519 → Управлять → Authorize**.
Для повторной проверки после подключения: `whoami`, `pwd`, `ls -ld /home/educadoc/educado.school`.
Не публиковать документацию с приватными ключами, паролями или URL с `cpsess`.

## DNS-подтверждение домена для OpenAI

20 ноября 2026 года в редакторе DNS-зоны cPanel для `educado.school` добавили
запись `TXT` с именем `openai-business-profile.educado.school.` и TTL 1200 секунд.
Значение берётся из настроек подтверждения домена в OpenAI Business Profile
и начинается с `openai-business-profile=`.

Точное значение записи проверили на `dns1.twinservers.net`,
`dns2.twinservers.net` и публичном резолвере `1.1.1.1`.
Для повторной проверки в PowerShell:

```powershell
Resolve-DnsName -Name openai-business-profile.educado.school -Type TXT -Server 1.1.1.1 -DnsOnly
```
