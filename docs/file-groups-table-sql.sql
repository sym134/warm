-- =====================================================
-- 文件分组表创建 SQL
-- =====================================================
-- 说明：用于存储文件分组的独立表，替代原来使用文件表 remark 字段存储分组名称的方式
-- 创建时间：2024

-- 1. 创建文件分组表
CREATE TABLE IF NOT EXISTS `system_file_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '分组ID',
  `name` varchar(100) NOT NULL COMMENT '分组名称',
  `file_type` enum('image','video','audio','file') DEFAULT NULL COMMENT '文件类型（可选，用于筛选）',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `created_by` tinyint(4) NOT NULL COMMENT '创建者',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_file_type` (`file_type`),
  KEY `idx_sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='文件分组';

-- 2. 修改 system_files 表，添加 group_id 字段（如果还没有）
-- 注意：如果 group_id 字段已存在，请跳过此步骤或根据实际情况修改

-- 检查 group_id 字段是否存在，如果不存在则添加
-- ALTER TABLE `system_files` ADD COLUMN IF NOT EXISTS `group_id` int(11) unsigned DEFAULT NULL COMMENT '分组ID' AFTER `remark`;

-- 如果 group_id 字段已存在但类型不对，需要先修改字段类型
-- ALTER TABLE `system_files` MODIFY COLUMN `group_id` int(11) unsigned DEFAULT NULL COMMENT '分组ID';

-- 3. 添加外键约束（可选，建议在生产环境添加）
-- 注意：如果表中已有数据，请先确保 group_id 的值都存在于 system_file_groups 表中，或为 NULL

-- 添加外键约束
ALTER TABLE `system_files` 
ADD CONSTRAINT `fk_system_files_group_id` 
FOREIGN KEY (`group_id`) 
REFERENCES `system_file_groups` (`id`) 
ON DELETE SET NULL 
ON UPDATE CASCADE;

-- 4. 添加索引（如果还没有）
-- 为 group_id 字段添加索引以提高查询性能
-- ALTER TABLE `system_files` ADD INDEX `idx_group_id` (`group_id`);

-- =====================================================
-- 数据迁移说明
-- =====================================================
-- 如果之前使用了 remark 字段存储分组名称，需要将数据迁移到新表：
-- 
-- 1. 查找所有分组标记文件（origin_name = '.group'）
--    SELECT * FROM system_files WHERE origin_name = '.group';
-- 
-- 2. 将分组数据迁移到新表（示例）
--    INSERT INTO system_file_groups (id, name, file_type, created_by, created_at)
--    SELECT CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(group_id, '_', -1), '_', 1) AS UNSIGNED) as id,
--           remark as name,
--           file_type,
--           created_by,
--           created_at
--    FROM system_files 
--    WHERE origin_name = '.group' AND group_id IS NOT NULL;
-- 
-- 3. 更新文件表的 group_id 为整数ID（如果之前使用的是字符串ID）
--    需要根据实际情况编写迁移脚本
-- 
-- 4. 删除旧的分组标记文件
--    DELETE FROM system_files WHERE origin_name = '.group';
