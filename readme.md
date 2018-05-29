# 繁盛王国

Demo 制作中。

# 开发流程

Fork 项目 -> 查阅 Teambition(TB) -> 领取任务 -> 创建分支 -> 开发完毕，提交 PR -> 审核，合并 PR -> 移除本地仓库的分支 -> 查阅 TB -> 领取任务 -> 循环...

## 需求

每个任务的需求统一发布在 TB 的任务列表中，整体的需求分析比较多，Demo 版本就不制作了（偷懒）。

## 开发规范

 - **换行符**统一为 Unix or OS x 格式（\n）；
 - **单行长度**最大：144 字符；
 - **变量命名规则**：名词 + 修饰动词（eg. `$peopleOccupy`）；
 - **方法注释**：每个方法都需要携带注释，并为参数、返回值等注释选项，提供合适的文字解释。推荐给中间件或类似的插件类，编写文件注释，并不强制。
 - **内部注释**：每个逻辑块都应携带对应注释，若注释存在层级关系，推荐 `/* Description */` 作为大块注释，`// Description` 作为小块注释。
 - **内部方法**：必须强制参数类型，或内部进行检测机制。

## Laravel 命令

创建模型（及数据库迁移文件）：
`php artisan make:model Models/ModelName -m`

创建中间件：
`php artisan make:middleware Name`

执行数据库迁移并填充数据：
`php artisan migrate --seed`

执行重置数据库迁移并填充数据：
`php artisan migrate:refresh --seed`

执行移除数据库迁移：
`php artisan migrate:rollback`
