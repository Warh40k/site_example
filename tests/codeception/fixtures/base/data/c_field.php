<?php

return [
    ['id' => '1', 'name' => 'title', 'title' => 'Наименование', 'entity' => '1', 'type' => 'varchar', 'link_type' => '', 'link_id' => '0', 'size' => '255', 'group' => '0', 'editor' => 'string', 'widget' => '', 'modificator' => '', 'validator' => 'set', 'def_value' => '', 'position' => '1', 'prohib_del' => '1', 'no_edit' => '1'],
    ['id' => '2', 'name' => 'article', 'title' => 'Артикул', 'entity' => '1', 'type' => 'varchar', 'link_type' => '', 'link_id' => '0', 'size' => '255', 'group' => '0', 'editor' => 'string', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '2', 'prohib_del' => '1', 'no_edit' => '1'],
    ['id' => '3', 'name' => 'alias', 'title' => 'Адрес', 'entity' => '1', 'type' => 'varchar', 'link_type' => '', 'link_id' => '0', 'size' => '255', 'group' => '0', 'editor' => 'string', 'widget' => '', 'modificator' => '', 'validator' => 'unique', 'def_value' => '', 'position' => '3', 'prohib_del' => '1', 'no_edit' => '1'],
    ['id' => '4', 'name' => 'gallery', 'title' => 'Фотогалерея', 'entity' => '1', 'type' => 'varchar', 'link_type' => '', 'link_id' => '14', 'size' => '255', 'group' => '0', 'editor' => 'gallery', 'widget' => 'gallery', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '4', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '5', 'name' => 'announce', 'title' => 'Краткое описание', 'entity' => '1', 'type' => 'text', 'link_type' => '', 'link_id' => '0', 'size' => '0', 'group' => '0', 'editor' => 'wyswyg', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '5', 'prohib_del' => '1', 'no_edit' => '1'],
    ['id' => '6', 'name' => 'obj_description', 'title' => 'Характеристики', 'entity' => '1', 'type' => 'text', 'link_type' => '', 'link_id' => '0', 'size' => '0', 'group' => '0', 'editor' => 'wyswyg', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '6', 'prohib_del' => '1', 'no_edit' => '1'],
    ['id' => '7', 'name' => 'price', 'title' => 'Цена', 'entity' => '1', 'type' => 'decimal', 'link_type' => '', 'link_id' => '0', 'size' => '0', 'group' => '0', 'editor' => 'money', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '7', 'prohib_del' => '1', 'no_edit' => '0'],
    ['id' => '8', 'name' => 'old_price', 'title' => 'Старая цена', 'entity' => '1', 'type' => 'varchar', 'link_type' => '', 'link_id' => '0', 'size' => '255', 'group' => '0', 'editor' => 'string', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '8', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '9', 'name' => 'measure', 'title' => 'Единица измерения', 'entity' => '1', 'type' => 'varchar', 'link_type' => '', 'link_id' => '0', 'size' => '255', 'group' => '0', 'editor' => 'string', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '9', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '10', 'name' => 'add_gallery', 'title' => 'Фотографии', 'entity' => '1', 'type' => 'varchar', 'link_type' => '', 'link_id' => '15', 'size' => '255', 'group' => '0', 'editor' => 'gallery', 'widget' => 'fotorama', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '10', 'prohib_del' => '1', 'no_edit' => '0'],
    ['id' => '11', 'name' => 'active', 'title' => 'Активность', 'entity' => '1', 'type' => 'int', 'link_type' => '', 'link_id' => '0', 'size' => '0', 'group' => '1', 'editor' => 'check', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '1', 'position' => '11', 'prohib_del' => '1', 'no_edit' => '0'],
    ['id' => '12', 'name' => 'buy', 'title' => 'Заказать', 'entity' => '1', 'type' => 'int', 'link_type' => '', 'link_id' => '0', 'size' => '0', 'group' => '1', 'editor' => 'check', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '1', 'position' => '12', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '13', 'name' => 'on_main', 'title' => 'Выводить на главную', 'entity' => '1', 'type' => 'int', 'link_type' => '', 'link_id' => '0', 'size' => '0', 'group' => '1', 'editor' => 'check', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '13', 'prohib_del' => '1', 'no_edit' => '0'],
    ['id' => '14', 'name' => 'hit', 'title' => 'Хит', 'entity' => '1', 'type' => 'int', 'link_type' => '', 'link_id' => '0', 'size' => '0', 'group' => '1', 'editor' => 'check', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '14', 'prohib_del' => '1', 'no_edit' => '0'],
    ['id' => '15', 'name' => 'new', 'title' => 'Новинка', 'entity' => '1', 'type' => 'int', 'link_type' => '', 'link_id' => '0', 'size' => '0', 'group' => '1', 'editor' => 'check', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '15', 'prohib_del' => '1', 'no_edit' => '0'],
    ['id' => '16', 'name' => 'discount', 'title' => 'Акция', 'entity' => '1', 'type' => 'int', 'link_type' => '', 'link_id' => '0', 'size' => '0', 'group' => '1', 'editor' => 'check', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '16', 'prohib_del' => '1', 'no_edit' => '0'],
    ['id' => '17', 'name' => 'countbuy', 'title' => 'Количество', 'entity' => '1', 'type' => 'int', 'link_type' => '', 'link_id' => '0', 'size' => '0', 'group' => '1', 'editor' => 'check', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '1', 'position' => '17', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '18', 'name' => 'title', 'title' => 'Название', 'entity' => '3', 'type' => 'varchar', 'link_type' => '', 'link_id' => '0', 'size' => '255', 'group' => '0', 'editor' => 'string', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '18', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '19', 'name' => 'priority', 'title' => 'priority', 'entity' => '3', 'type' => 'int', 'link_type' => '', 'link_id' => '0', 'size' => '0', 'group' => '0', 'editor' => 'int', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '19', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '20', 'name' => 'alias', 'title' => 'Техническое имя', 'entity' => '3', 'type' => 'varchar', 'link_type' => '', 'link_id' => '0', 'size' => '255', 'group' => '0', 'editor' => 'string', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '20', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '21', 'name' => 'title', 'title' => 'Название', 'entity' => '4', 'type' => 'varchar', 'link_type' => '', 'link_id' => '0', 'size' => '255', 'group' => '0', 'editor' => 'string', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '21', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '22', 'name' => 'priority', 'title' => 'priority', 'entity' => '4', 'type' => 'int', 'link_type' => '', 'link_id' => '0', 'size' => '0', 'group' => '0', 'editor' => 'int', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '22', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '23', 'name' => 'alias', 'title' => 'Техническое имя', 'entity' => '4', 'type' => 'varchar', 'link_type' => '', 'link_id' => '0', 'size' => '255', 'group' => '0', 'editor' => 'string', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '23', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '24', 'name' => 'title', 'title' => 'Название', 'entity' => '5', 'type' => 'varchar', 'link_type' => '', 'link_id' => '0', 'size' => '255', 'group' => '0', 'editor' => 'string', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '24', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '25', 'name' => 'priority', 'title' => 'priority', 'entity' => '5', 'type' => 'int', 'link_type' => '', 'link_id' => '0', 'size' => '0', 'group' => '0', 'editor' => 'int', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '25', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '26', 'name' => 'alias', 'title' => 'Техническое имя', 'entity' => '5', 'type' => 'varchar', 'link_type' => '', 'link_id' => '0', 'size' => '255', 'group' => '0', 'editor' => 'string', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '26', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '27', 'name' => 'title', 'title' => 'Название', 'entity' => '8', 'type' => 'varchar', 'link_type' => '', 'link_id' => '0', 'size' => '255', 'group' => '0', 'editor' => 'string', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '27', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '28', 'name' => 'priority', 'title' => 'priority', 'entity' => '8', 'type' => 'int', 'link_type' => '', 'link_id' => '0', 'size' => '0', 'group' => '0', 'editor' => 'int', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '28', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '29', 'name' => 'alias', 'title' => 'Техническое имя', 'entity' => '8', 'type' => 'varchar', 'link_type' => '', 'link_id' => '0', 'size' => '255', 'group' => '0', 'editor' => 'string', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '29', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '30', 'name' => 'cvet', 'title' => 'cvet', 'entity' => '7', 'type' => 'varchar', 'link_type' => '-<', 'link_id' => '3', 'size' => '255', 'group' => '0', 'editor' => 'select', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '30', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '31', 'name' => 'material_remeshka', 'title' => 'material_remeshka', 'entity' => '7', 'type' => 'varchar', 'link_type' => '-<', 'link_id' => '5', 'size' => '255', 'group' => '0', 'editor' => 'select', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '31', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '32', 'name' => 'material_korpusa', 'title' => 'material_korpusa', 'entity' => '7', 'type' => 'varchar', 'link_type' => '-<', 'link_id' => '4', 'size' => '255', 'group' => '0', 'editor' => 'select', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '32', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '33', 'name' => 'brend', 'title' => 'brend', 'entity' => '7', 'type' => 'int', 'link_type' => '-<', 'link_id' => '12', 'size' => '0', 'group' => '0', 'editor' => 'collection', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '33', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '34', 'name' => 'stil', 'title' => 'stil', 'entity' => '7', 'type' => 'int', 'link_type' => '-<', 'link_id' => '13', 'size' => '0', 'group' => '0', 'editor' => 'collection', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '34', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '35', 'name' => 'specialnye_funkcii', 'title' => 'Специальные функции', 'entity' => '7', 'type' => 'varchar', 'link_type' => '><', 'link_id' => '8', 'size' => '255', 'group' => '0', 'editor' => 'multiselect', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '35', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '36', 'name' => 'naznachenie', 'title' => 'Назначение', 'entity' => '7', 'type' => 'varchar', 'link_type' => '><', 'link_id' => '14', 'size' => '255', 'group' => '0', 'editor' => 'multicollection', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '36', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '37', 'name' => 'title', 'title' => 'Название', 'entity' => '12', 'type' => 'varchar', 'link_type' => '', 'link_id' => '0', 'size' => '255', 'group' => '0', 'editor' => 'string', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '37', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '38', 'name' => 'alias', 'title' => 'Псевдоним', 'entity' => '12', 'type' => 'varchar', 'link_type' => '', 'link_id' => '0', 'size' => '255', 'group' => '0', 'editor' => 'string', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '38', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '39', 'name' => 'gallery', 'title' => 'Галерея', 'entity' => '12', 'type' => 'varchar', 'link_type' => '', 'link_id' => '16', 'size' => '255', 'group' => '0', 'editor' => 'gallery', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '39', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '40', 'name' => 'info', 'title' => 'Описание', 'entity' => '12', 'type' => 'text', 'link_type' => '', 'link_id' => '0', 'size' => '0', 'group' => '0', 'editor' => 'wyswyg', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '40', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '41', 'name' => 'active', 'title' => 'Активность', 'entity' => '12', 'type' => 'int', 'link_type' => '', 'link_id' => '0', 'size' => '0', 'group' => '3', 'editor' => 'check', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '41', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '42', 'name' => 'on_main', 'title' => 'На главной', 'entity' => '12', 'type' => 'int', 'link_type' => '', 'link_id' => '0', 'size' => '0', 'group' => '3', 'editor' => 'check', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '42', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '43', 'name' => 'last_modified_date', 'title' => 'Дата модификации', 'entity' => '12', 'type' => 'datetime', 'link_type' => '', 'link_id' => '0', 'size' => '0', 'group' => '3', 'editor' => 'datetime', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '43', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '44', 'name' => 'title', 'title' => 'Название', 'entity' => '13', 'type' => 'varchar', 'link_type' => '', 'link_id' => '0', 'size' => '255', 'group' => '0', 'editor' => 'string', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '44', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '45', 'name' => 'alias', 'title' => 'Псевдоним', 'entity' => '13', 'type' => 'varchar', 'link_type' => '', 'link_id' => '0', 'size' => '255', 'group' => '0', 'editor' => 'string', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '45', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '46', 'name' => 'gallery', 'title' => 'Галерея', 'entity' => '13', 'type' => 'varchar', 'link_type' => '', 'link_id' => '16', 'size' => '255', 'group' => '0', 'editor' => 'gallery', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '46', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '47', 'name' => 'info', 'title' => 'Описание', 'entity' => '13', 'type' => 'text', 'link_type' => '', 'link_id' => '0', 'size' => '0', 'group' => '0', 'editor' => 'wyswyg', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '47', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '48', 'name' => 'active', 'title' => 'Активность', 'entity' => '13', 'type' => 'int', 'link_type' => '', 'link_id' => '0', 'size' => '0', 'group' => '5', 'editor' => 'check', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '48', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '49', 'name' => 'on_main', 'title' => 'На главной', 'entity' => '13', 'type' => 'int', 'link_type' => '', 'link_id' => '0', 'size' => '0', 'group' => '5', 'editor' => 'check', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '49', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '50', 'name' => 'last_modified_date', 'title' => 'Дата модификации', 'entity' => '13', 'type' => 'datetime', 'link_type' => '', 'link_id' => '0', 'size' => '0', 'group' => '5', 'editor' => 'datetime', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '50', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '51', 'name' => 'title', 'title' => 'Название', 'entity' => '14', 'type' => 'varchar', 'link_type' => '', 'link_id' => '0', 'size' => '255', 'group' => '0', 'editor' => 'string', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '51', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '52', 'name' => 'alias', 'title' => 'Псевдоним', 'entity' => '14', 'type' => 'varchar', 'link_type' => '', 'link_id' => '0', 'size' => '255', 'group' => '0', 'editor' => 'string', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '52', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '53', 'name' => 'gallery', 'title' => 'Галерея', 'entity' => '14', 'type' => 'varchar', 'link_type' => '', 'link_id' => '16', 'size' => '255', 'group' => '0', 'editor' => 'gallery', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '53', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '54', 'name' => 'info', 'title' => 'Описание', 'entity' => '14', 'type' => 'text', 'link_type' => '', 'link_id' => '0', 'size' => '0', 'group' => '0', 'editor' => 'wyswyg', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '54', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '55', 'name' => 'active', 'title' => 'Активность', 'entity' => '14', 'type' => 'int', 'link_type' => '', 'link_id' => '0', 'size' => '0', 'group' => '7', 'editor' => 'check', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '55', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '56', 'name' => 'on_main', 'title' => 'На главной', 'entity' => '14', 'type' => 'int', 'link_type' => '', 'link_id' => '0', 'size' => '0', 'group' => '7', 'editor' => 'check', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '56', 'prohib_del' => '0', 'no_edit' => '0'],
    ['id' => '57', 'name' => 'last_modified_date', 'title' => 'Дата модификации', 'entity' => '14', 'type' => 'datetime', 'link_type' => '', 'link_id' => '0', 'size' => '0', 'group' => '7', 'editor' => 'datetime', 'widget' => '', 'modificator' => '', 'validator' => '', 'def_value' => '', 'position' => '57', 'prohib_del' => '0', 'no_edit' => '0'],
];
