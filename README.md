# php-multiprocess
php multiprocess management, php多进程管理器，管理ignore_user_abort()形成的后台程序。
php使用ignore_user_abort()和set_time_limit(0)可以实现php程序的后台执行，但关闭浏览器进程后，无法看到有多少进程在运行，也无法停止进程。
本程序可以非常方便快捷地管理ignore_user_abort()形成的后台程序。

- 主程序和思路都在这里，没有放依赖。
- 如须依赖请github联系本人

# 主要函数
- function __construct(){
- function __destruct(){
- function check2stop($status=1){
- function setstop(){
- function showlist(){
- function start(){
- function stop($stop=true){
- function tbl_create_process(){
