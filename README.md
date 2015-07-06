[PHP] Hush-Framework in Action

Hush Framework（以下简称 HF）是一个基于 ZendFramework 和 Smarty 的强大的面向企业应用的 PHP 框架，Google Code 上的项目地址为：https://github.com/jameschz/hush ，有兴趣的朋友可以看看，本次 Update 除了上次介绍的 HF 一些功能点外（参考上篇文章：《[PHP] 新的里程碑 Hush Framework 》），添加了对 Bpm 企业工作流控制的支持以及关于 PHP 多进程 / 消息处理 的模块，力求把 PHP 的潜力发挥到最大~ 关于 Bpm 方向大家可以参考子项目 PBPM；关于 PHP 消息处理方向，我还开辟了一个子项目：PMS Framework（https://github.com/jameschz/hush），下期我会专门找时间介绍一下这个消息队列处理系统。

目前这三个项目都在不断的完善中，有兴趣加入以上项目的朋友可以和我联系，本人的邮件地址见本文最后。

以下是关于 HF 一些安装和使用说明：

1、下载与安装：

介绍安装过程之前先介绍一下 HF 的大体结构，代码包括三大部分：
hush-app：HF 应用框架，支持 RBAC 和 BPM 的强大企业级框架
hush-lib：HF 基础框架，hush-app 应用框架的基础，基于 ZendFramework 和 Smarty
hush-pms：(Message Queue Server) 可参考 PMS Framework 项目

首先，通过 GIT 下载源码：
git clone https://github.com/jameschz/hush.git

另外，新版的 Hush Framework 通过系统初始化命令 “hush sys init”（详见以下命令行说明部分）即可自动安装所需的第三方类库。所有第三方类库会被默认安装到与 Hush Framework 同目录的 phplibs（目录由你自己决定） 下。

此外，开发者也可以在 hush-app/etc/global.config.php 配置文件中修改第三方类库的安装目录：

...
/**
 * Common libraries paths
 * TODO : Copy Zend Framework and Smarty libraries to this path !!!
 */
define('__COMM_LIB_DIR', /path/to/phplibs);
...

以上是全局配置，至于前后台站点的具体配置，大家可以分别参考 hush-app/etc/frontend.config.php 和 hush-app/etc/backend.config.php 配置文件。

接下来，修改 hush-app/etc/database.mysql.ini 配置文件中的数据库配置，保证数据库服务器用户名/密码正确。该配置文件支持分库/分组策略，$_clusters 属性内用于配置数据库服务器群的信息，既可以在 doShardDb 方法中设置分库策略，也可以在 doShardTable 方法中设置分表策略。

注意事项：

A、默认数据库配置可参考 hush-lib/Hush/Db/Config.php 类中 const 变量，数据库地址 127.0.0.1、端口 3306、用户名 root、密码 passwd，建议大家在安装数据库时使用此默认配置，这样就免去了修改配置这一步。当然，在大型项目中，还可以需要根据实际情况修改 hush-app/etc/database.mysql.php 中的数据库集群的分库、分表策略。

B、由于脚本需要使用到 php 和 mysql 等可执行命令，比如在 Windows 环境中，我们需要把 php.exe 和 mysql.exe 文件的所在目录加入到系统的 Path 环境变量中（右键我的电脑 -> 高级 -> 环境变量）。

然后，使用命令行进入 hush-app/bin 目录，运行 hush 命令，可以看到如下画面：



运行 hush 命令即可显示使用提示，以下是主要命令的说明：
hush sys init	初始化 hush 运行环境（仅首次运行用，数据库配置请在 hush-app/etc/database.mysql.php 文件中配置）
hush sys newapp (新增)
新建项目，运行命令并按照提示输入新项目的命名空间以及新项目的目录，即可在新项目目录下自动创建项目代码，新项目的代码将全部从当前项目中拷贝（命名空间会改变），此功能涉及面比较广，建议在初建项目的时候使用
hush sys newdao (新增)
新建数据操作类（DAO），运行命令并按照提示输入数据库类名和表类名 SimpleDatabaseName\SimpleTableName，即可在 lib/Ihush/Dao 目录下自动创建类代码
hush sys newctrl (新增)
新建控制器类（Controller），运行命令并按照提示输入控制器路径和类名 Backend\Page\ControllerName，即可在 lib/Ihush/App 目录下自动创建类代码，以及在 tpl/ 对应目录下面添加模板文件
hush db [backup|recover] <...>	备份数据库，支持备份&恢复任何一个分布式的数据库，<...> 中按照 hush-app/etc/database.mysql.ini 配置文件中 $_clusters 属性层次来选择，假如要备份首个服务器的 ihush_apps 库则需要输入 hush db backup default:0:master:0:ihush_apps 命令
hush check [dir|configs]	检查系统 [ 目录 | 配置 ] 的正确性
hush check all	检查整个框架的正确性
hush clean tpl [fe|be] [tplc|cache]	清除 [ 前台 | 后台 ] 的Smarty模板中的 [ 临时模板 | 模板缓存 ] 的文件
hush clean cache [fe|be]	清除 [ 前台 | 后台 ] 的本地缓存文件
hush clean all	清除模板和缓存的全部内容
第一次安装运行时，我们必须执行 hush sys init 命令开始初始化 HF 的运行环境。选择 Y 后，系统会自动检查、下载并安装第三方类库，然后导入初始数据库、建立运行时必要的目录、检查前后台站点必要的配置并清除所有老的缓存数据。如果在执行过程中报错，请查看数据配置是否正确。运行成功，将显示 “Thank you for using Hush Framework !!!” 的提示。

运行完毕后，我们最后只需要配置 Apache 服务器的前后台站点 VirtualHost 分别到 hush-app/web/frontend 和 hush-app/web/backend 目录下，并重启 Apache 服务就可以了。Apache 会自动根据 .htaccess 文件做 URL 重写，如果是其他的服务器（例如 Nginx）请把对应 VirtualHost 的所有请求转发至 index.php。

以下是 Apache 虚拟主机的配置示例（建议放到 extra/http-vhost.conf 文件中），请参考：

<VirtualHost *:80>
    DocumentRoot "D:/workspace/hush-framework/hush-app/web/backend"
    ServerName hush-app-backend
    <Directory />
        AllowOverride All
        Order deny,allow
        Allow from all
    </Directory>
</VirtualHost>

<VirtualHost *:80>
    DocumentRoot "D:/workspace/hush-framework/hush-app/web/frontend"
    ServerName hush-app-frontend
    <Directory />
        AllowOverride All
        Order deny,allow
        Allow from all
    </Directory>
</VirtualHost>

最后，由于使用了 VirtualHost 的虚拟地址，我们还需要修改系统的 hosts 文件，也就是 Windows 系统中的 C:\WINDOWS\system32\drivers\etc\hosts 文件，加入如下配置：

127.0.0.1 hush-app-frontend
127.0.0.1 hush-app-backend

访问前台 URL 地址：http://hush-app-frontend，即可打开实例的前台站点，效果如下图：



访问后台 URL 地址：http://hush-app-frontend，使用默认的超级用户名/密码：sa/sa 登录即可进入实例的后台站点，截图如下：



如果您能看到如上界面，那么恭喜您安装成功，接下来就开始您的 HF 之旅吧~

2、HF 开发参考：

本框架已经为使用者准备好完善的 MVC 结构，对应的类代码可以分别在以下类目录下看到：
M ：hush-app/lib/Ihush/Dao （数据库操作类，按库名分开）
V ：hush-app/tpl/frontend/template 和 hush-app/tpl/backend/template （分别对应前后台）
C ：hush-app/lib/Ihush/App/Frontend 和 hush-app/lib/Ihush/App/Backend （分别对应前后台）
这里需要注意的是：传统的 Controller 我们这里叫 Page，比如 hush-app/lib/Ihush/App/Backend/Page/AclPage.php 就是对应 URL 地址为 /acl/* 的控制器，我们这里的 Controller 类是以 Page 为后缀的原因是 HF 框架不仅支持 MVC 结构的应用，也支持传统的基于页面的 PHP 程序结构，大家可以参考 hush-app/web/frontend/app/index.php （对应 URL 为 /app/index.php） 的类代码。

至于 Mapping 配置文件，大家可以参考 hush-app/etc/frontend.mapping.ini 和 hush-app/etc/backend.mapping.ini 文件，这就是 HF 的 URL Router 大大快于 ZF 的秘密了，我们支持用配置文件直接匹配 URL 与 Controller 的关系，示例如下：
/test/mapping       = TestPage::mappingAction
/test/p/*               = TestPage::pagingAction
/test/*                  = TestPage::indexAction

然后这里重点介绍一下后台的 RBAC 的可扩展的权限管理系统，这个系统应该是传统 ERP 系统中最终要的部分之一，当然也应该可以算的上是 HF 中最核心的部分之一了，我们来看看如何使用和扩展这个核心权限系统吧。首先必须了解这个系统是包括：角色管理，用户管理，资源管理和菜单管理四个部分。

我们打开后台地址，使用 sa 帐户登陆后，可以根据自己的需求来添加角色，系统默认有超级管理员（SA）、普通管理员（AM）、客服人员（CS）、物流人员（LS）、财务人员（FS）五个角色，当然在实际运用中我们还加入了客服主管、物流主管以及财务主管等各层次的角色以满足公司内部更细致的权限控制要求。然后，我们可以通过设置角色的权限来控制每个角色可操作的其他角色，比如超级管理员拥有所其他所有角色的操作权限，而普通管理员则拥有对客服人员、物流人员、财务人员的管理权限。这样一个系统内部最核心的角色权限关系就被定义好了（程序使用的时候该关系会被 Cache 起来以供系统调用）。

然后我们就可以通过用户管理来添加和修改每个用户的角色（一个用户可以有多个角色），最后就是给资源和菜单设置权限了。关于资源的概念大家可以去参考以下 ACL 的设计原则，其实简单点说，资源就是就是用户/角色可以操作的权限实体，菜单（我们把每个菜单对应的页面称为应用）是比较传统的一个说法，而对于 HF 的 ACL 来说，实际上是一种特殊的资源，我们可以控制每个菜单的权限来限定是有特定角色的用户才可以操作该菜单下的应用，让后通过设置更细节的资源来控制应用中的细节的权限，这样子就构成了一个比较完善的后台权限管理框架，菜单管理的操作界面如下图：



而开发者使用起来也很简单，HF 在程序代码中我们提供一个 acl 对象，只要使用其中的 isAllowed 方法就可以很方便的判断用户的角色列表是否对相应的资源有效，甚至在 Smarty 模板中我们也可以很方便的进行权限的判断工作。这样子就大大地减少了这部分繁琐的权限控制代码了，赶快来尝试一下吧~

另外，本框架还集成了一个 BPM 的工作流系统，也是 PBPM 项目（http://code.google.com/p/pbpm/）的主要内容，目前已经可以支持通过手动配置流程图来实现可编程的企业流程管理（内嵌类 PHP 语言 PBEL），这应该是 PHP 在这个领域的“先行者”了吧，有兴趣的朋友千万不能错过哦，后面我也会找时间详细介绍一下这个系统，先预览一下系统截图：



接下来，对于 hush-lib 下的 HF 类库，我做一个简单的说明如下，详细的大家请下载文档查阅：

Hush_Acl：ACL 权限管理包
Hush_App：App 基类包，主要用户开使整个 webapp 的逻辑。
Hush_Auth：登录验证类包，参考 Zend_Auth
Hush_Bpm：BPM 流程管理类库，包含 Pbel 语言引擎
Hush_Cache：缓存类包，参考 Zend_Cache
Hush_Chart：图表类包
Hush_Crypt：加密类库，支持 Rsa
Hush_Date：时间类包，参考 Zend_Date
Hush_Db：数据库操作类包（MVC 中的 M），参考 Zend_Db
Hush_Debug：调试类包
Hush_Exception：异常类包
Hush_Html：Html 类库，可用于构建 Form
Hush_Http：Http 操作类包
Hush_Json：参考 Zend_Json
Hush_Mail：邮件控制类包
Hush_Message：消息处理类包
Hush_Page：页面类（MVC 中的 C）
Hush_Paging：分页类
Hush_Process：进程类包
Hush_Session：参考 Zend_Session
Hush_Socket：Socket 处理类包
Hush_Tree：数型结构处理类
Hush_Util：常用工具类
Hush_View：页面显示类包（MVC 中的 V）

至于以上类库具体的使用，大家可以参考 hush-app 的源码，另外可以参考 hush-lib/Examples 下的几个例子：包含一个多进程的例子，一个消息处理类，一个 Socket Server/Client 的例子，以及一个多进程 C/S 结构的实例，均可直接使用 cli 方式运行，当然首先您需要根据提示打开一些必要的 PHP 扩展，如果有问的话题可以和我联系~

关于分页类使用“特别说明”：

这段时间从某些朋友的留言中可以看出大家可能对 Hush Framework 的分页类的使用有一些疑惑，因此我在这里特别说明一下这个部分的用法（大家也可以参考 hush-app/lib/Ihush/App/Frontend/Page/TestPage.php 中的 pagingDemo 方法中的说明，使用说明已经 checkin），另外也请大家更新一下 Hush Framework 的代码。本分页类的实用方式其实比 Zend Paginator 简单很多，用户直接可以使用分页类的 toArray() 方法把分页结果的数组直接提供给 Smarty 等模板直接展示，非常方便！另外，本分页类的一大亮点就是：本分页类与 DB 层没有任何耦合，大家完全可以更据不同需求灵活运用，也不存在大数据量下 count 效率的问题，具体大家请看下面的使用说明。

从比较全面的角度考虑，本框架的分页支持三种方式：
1、构造函数的第一个参数是数组：针对普通数组的分页，也就是 TestPage.php 中 pagingDemo Action 的使用，如果需要分页的数据是现成的话，建议使用这种简单方式。
2、构造函数的第一个参数是数字：常在 DAO 类中使用，参数表示查询出的总数，然后可使用分页类中的 frNum 和 toNum 数值结合 MySQL 的 limit 使用。举例如下：
...
$sql = $this->dbr()->select()... // 查找语句
$page = new Ihush_Paging($totalNumber, $eachPageNumber, $thisPageNumber, array(...));
$sql->limit($page->frNum, $page->toNum); // 结合 Limit 分页
...
当然如果你要使用 Zend Db 自带的 limitPage 方法也是可以的，具体的实例见：hush-app/lib/Ihush/Dao/Core/BpmRequest.php 中的 getSendByPage() 方法的用法。
3、构造函数的第一个参数为空：这种方式经常用在后台数据量爆大的情况，由于不需要传入 count 总数，也避免了大数量的情况下出现的 count 的效率问题，大家可以灵活运用。

另外，Ihush_Paging 类四个参数的解释说明如下：

参数1：具体用法见本方法前面的“特别说明”。
参数2：每页包含的数据项个数。
参数3：页码数，空则表示首页。
参数4：分页模式，目前支持的 Mode 有三种，分别是 Google、Common、JavaEye 的分页模式。

最后，欢迎有兴趣加入的朋友或者有好的建议的朋友和我联系，我的邮件地址为：huangjuanshi#163.com （# 替换成 @），希望我们的努力让 HF 和 PMS 能给 PHP 的发展贡献一份微薄的力量~
