# Amis 类方法重命名清单

本文档列出了 `warm\admin\renderer\Amis` 类中**方法名相对于旧版本发生变更**的情况，便于排查兼容性问题。

## 方法重命名列表

| 原方法名                   | 新方法名           |
|----------------------------|--------------------|
| `ArrayControl()`           | `InputArray()`     |
| `ButtonGroupControl()`     | `ButtonGroupSelect()` |
| `ChainedSelectControl()`   | `ChainedSelect()`  |
| `CheckboxControl()`        | `Checkbox()`       |
| `CheckboxesControl()`      | `Checkboxes()`     |
| `ComboControl()`           | `Combo()`          |
| `ConditionBuilderControl()`| `ConditionBuilder()` |
| `DateControl()`            | `InputDate()`      |
| `DateRangeControl()`       | `InputDateRange()` |
| `DateTimeControl()`        | `InputDateTime()`  |
| `DiffControl()`            | `DiffEditor()`     |
| `EditorControl()`          | `Editor()`         |
| `FieldSetControl()`        | `FieldSet()`       |
| `FileControl()`            | `InputFile()`      |
| `FormControl()`            | `Form()`           |
| `FormulaControl()`         | `Formula()`        |
| `GroupControl()`           | `Group()`          |
| `HiddenControl()`          | `Hidden()`         |
| `IconPickerControl()`      | `IconPicker()`     |
| `ImageControl()`           | `InputImage()`     |
| `InputCityControl()`       | `InputCity()`      |
| `InputColorControl()`      | `InputColor()`     |
| `InputGroupControl()`      | `InputGroup()`     |
| `InputPasswordControl()`   | `InputPassword()`  |
| `JSONSchemaEditorControl()`| `JSONSchemaEditor()` |
| `ListControl()`            | `ListRenderer()`   |
| `LocationControl()`        | `LocationPicker()` |
| `MatrixControl()`          | `MatrixCheckboxes()` |
| `MonthControl()`           | `InputMonth()`     |
| `MonthRangeControl()`      | `InputMonthRange()`|
| `NestedSelectControl()`    | `NestedSelect()`   |
| `NumberControl()`          | `InputNumber()`    |
| `PickerControl()`          | `Picker()`         |
| `QuarterControl()`         | `InputQuarter()`   |
| `QuarterRangeControl()`    | `InputQuarterRange()` |
| `RadioControl()`           | `Radio()`          |
| `RadiosControl()`          | `Radios()`         |
| `RangeControl()`           | `InputRange()`     |
| `RatingControl()`          | `InputRating()`    |
| `RepeatControl()`          | `InputRepeat()`    |
| `RichTextControl()`        | `InputRichText()`  |
| `SelectControl()`          | `Select()`         |
| `SubFormControl()`         | `InputSubForm()`   |
| `SwitchControl()`          | `Switch()`         |
| `TableControl()`           | `inputTable()`     |
| `TabsTransferControl()`    | `TabsTransfer()`   |
| `TabsTransferPickerControl()` | `TabsTransferPicker()` |
| `TagControl()`             | `InputTag()`       |
| `TextControl()`            | `InputText()`      |
| `TextareaControl()`        | `Textarea()`       |
| `TimeControl()`            | `InputTime()`      |
| `TransferControl()`        | `Transfer()`       |
| `TransferPickerControl()`  | `TransferPicker()` |
| `TreeControl()`            | `InputTree()`      |
| `TreeSelectControl()`      | `TreeSelect()`     |
| `UUIDControl()`            | `UUID()`           |
| `UserSelectControl()`      | `UserSelect()`     |
| `YearControl()`            | `InputYear()`      |
| `StaticExactControl()`     | `Static()`         |
| `WangEditor()`             | `CustomWangEditor()` |
| `Watermark()`              | `CustomWatermark()` |
| `CRUD2Table()`             | `CRUD2()`          |
| `CRUDTable()`              | `CRUD()`           |
| `TableSchema2()`           | `Table2()`         |
| `VanillaAction()`          | （已移除或未保留） |

> 上表仅列出在旧版 `Amis.php` 中出现、且在当前 `Amis.php` 中能找到对应替代方法的重命名关系；如果后续继续调整命名，请同步维护本清单。

