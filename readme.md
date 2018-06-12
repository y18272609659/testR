# 繁盛王国

Demo 制作中。

# 开发流程

Fork 项目 -> 查阅 Teambition(TB) -> 领取任务 -> 创建分支 -> 开发完毕，提交 PR -> 审核，合并 PR -> 移除本地仓库的分支 -> 查阅 TB -> 领取任务 -> 循环...

## 需求

每个任务的需求统一发布在 TB 的任务列表中，整体的需求分析比较多，Demo 版本就不制作了（偷懒）。

## 开发规范

 - **换行符**：统一为 Unix or OS x 格式（\n）；
 - **单行长度**：最大 144 字符；
 - **变量命名规则**：名词 + 修饰动词（eg. `$peopleOccupy`）；
 - **命名规则**：变量、函数、方法 - 小写开头驼峰式，类名 - 大写开头驼峰式，常量 - 全大写下划线分割；
     - **词汇搭配**：名词 + 动词 ~文件/类/变量采用`名词+动词`，方法则采用 `名词+动词`~；
 - **方法注释**：每个 PHP 文件\类、方法，都需要携带注释；
 - **内部注释**：每个逻辑块都应携带对应注释，若注释存在层级关系，推荐 `/* Description */` 作为大块注释，`// Description` 作为小块注释；
 - **方法参数**：必须强制参数类型，对混合类型必须进行校验；
 - **判断语句的容错**：在有限可能性的判断语句中，用 `else` 触发日志系统(eg.01)。复合状态（多种条件共同判断）不受此限制；
 - **Commit**：言之有物，善用 `git stash`。如果你创建了一个 `interim commit`，自己去变基掉，否则别妄想合并那条 PR。

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

## 规范示例

> 为部分较为抽象的规范提供示例，欢迎交流。

### 01

```php
// 共有三种单调状态，但此处仅有 a/b 两种单调状态：
if ($param === 'a') {
    // do something...
} elseif ($param === 'b') {
    // do something...
} else {
    // 触发日志
}
```
