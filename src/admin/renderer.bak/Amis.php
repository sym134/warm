<?php

namespace warm\admin\renderer\expand\renderer;

use warm\admin\renderer\expand\renderer\expand\expand\AjaxAction;

class Amis
{
    /**
     * 创建 Amis 构建器实例.
     *
     * @return self
     */
    public static function make(): self
    {
        return new self();
    }

    /**
     * 创建 Action 渲染器实例.
     *
     * @return Action
     */
    public function Action(): Action
    {
        return Action::make();
    }

    /**
     * 创建 AjaxAction 渲染器实例.
     *
     * @return AjaxAction
     */
    public function AjaxAction(): AjaxAction
    {
        return AjaxAction::make();
    }

    /**
     * 创建 Alert 渲染器实例.
     *
     * @return Alert
     */
    public function Alert(): Alert
    {
        return Alert::make();
    }

    /**
     * 创建 AnchorNav 渲染器实例.
     *
     * @return AnchorNav
     */
    public function AnchorNav(): AnchorNav
    {
        return AnchorNav::make();
    }

    /**
     * 创建 AnchorNavSection 渲染器实例.
     *
     * @return AnchorNavSection
     */
    public function AnchorNavSection(): AnchorNavSection
    {
        return AnchorNavSection::make();
    }

    /**
     * 创建 InputArray 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return InputArray
     */
    public function InputArray($name = '', $label = ''): InputArray
    {
        $instance = InputArray::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Audio 渲染器实例.
     *
     * @return Audio
     */
    public function Audio(): Audio
    {
        return Audio::make();
    }

    /**
     * 创建 AutoFillHeight 渲染器实例.
     *
     * @return AutoFillHeight
     */
    public function AutoFillHeight(): AutoFillHeight
    {
        return AutoFillHeight::make();
    }

    /**
     * 创建 AutoGenerateFilter 渲染器实例.
     *
     * @return AutoGenerateFilter
     */
    public function AutoGenerateFilter(): AutoGenerateFilter
    {
        return AutoGenerateFilter::make();
    }

    /**
     * 创建 Avatar 渲染器实例.
     *
     * @return Avatar
     */
    public function Avatar(): Avatar
    {
        return Avatar::make();
    }

    /**
     * 创建 Badge 渲染器实例.
     *
     * @return Badge
     */
    public function Badge(): Badge
    {
        return Badge::make();
    }

    /**
     * 创建 Barcode 渲染器实例.
     *
     * @return Barcode
     */
    public function Barcode(): Barcode
    {
        return Barcode::make();
    }

    /**
     * 创建 BaseApi 渲染器实例.
     *
     * @return BaseApi
     */
    public function BaseApi(): BaseApi
    {
        return BaseApi::make();
    }

    /**
     * 创建 BaseRenderer 渲染器实例.
     *
     * @return BaseRenderer
     */
    public function BaseRenderer(): BaseRenderer
    {
        return BaseRenderer::make();
    }

    /**
     * 创建 Breadcrumb 渲染器实例.
     *
     * @return Breadcrumb
     */
    public function Breadcrumb(): Breadcrumb
    {
        return Breadcrumb::make();
    }

    /**
     * 创建 Button 渲染器实例.
     *
     * @return Button
     */
    public function Button(): Button
    {
        return Button::make();
    }

    /**
     * 创建 ButtonGroup 渲染器实例.
     *
     * @return ButtonGroup
     */
    public function ButtonGroup(): ButtonGroup
    {
        return ButtonGroup::make();
    }

    /**
     * 创建 ButtonGroupSelect 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return ButtonGroupSelect
     */
    public function ButtonGroupSelect($name = '', $label = ''): ButtonGroupSelect
    {
        $instance = ButtonGroupSelect::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 ButtonToolbar 渲染器实例.
     *
     * @return ButtonToolbar
     */
    public function ButtonToolbar(): ButtonToolbar
    {
        return ButtonToolbar::make();
    }

    /**
     * 创建 CRUD2Cards 渲染器实例.
     *
     * @return CRUD2Cards
     */
    public function CRUD2Cards(): CRUD2Cards
    {
        return CRUD2Cards::make();
    }

    /**
     * 创建 CRUD2List 渲染器实例.
     *
     * @return CRUD2List
     */
    public function CRUD2List(): CRUD2List
    {
        return CRUD2List::make();
    }

    /**
     * 创建 CRUD2 渲染器实例.
     *
     * @return CRUD2
     */
    public function CRUD2(): CRUD2
    {
        return CRUD2::make();
    }

    /**
     * 创建 CRUDCards 渲染器实例.
     *
     * @return CRUDCards
     */
    public function CRUDCards(): CRUDCards
    {
        return CRUDCards::make();
    }

    /**
     * 创建 CRUDList 渲染器实例.
     *
     * @return CRUDList
     */
    public function CRUDList(): CRUDList
    {
        return CRUDList::make();
    }

    /**
     * 创建 CRUD 渲染器实例.
     *
     * @return CRUD
     */
    public function CRUD(): CRUD
    {
        return CRUD::make();
    }

    /**
     * 创建 Calendar 渲染器实例.
     *
     * @return Calendar
     */
    public function Calendar(): Calendar
    {
        return Calendar::make();
    }

    /**
     * 创建 Card 渲染器实例.
     *
     * @return Card
     */
    public function Card(): Card
    {
        return Card::make();
    }

    /**
     * 创建 Card2 渲染器实例.
     *
     * @return Card2
     */
    public function Card2(): Card2
    {
        return Card2::make();
    }

    /**
     * 创建 Cards 渲染器实例.
     *
     * @return Cards
     */
    public function Cards(): Cards
    {
        return Cards::make();
    }

    /**
     * 创建 Carousel 渲染器实例.
     *
     * @return Carousel
     */
    public function Carousel(): Carousel
    {
        return Carousel::make();
    }

    /**
     * 创建 ChainedSelect 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return ChainedSelect
     */
    public function ChainedSelect($name = '', $label = ''): ChainedSelect
    {
        $instance = ChainedSelect::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Chart 渲染器实例.
     *
     * @return Chart
     */
    public function Chart(): Chart
    {
        return Chart::make();
    }

    /**
     * 创建 ChartRadios 渲染器实例.
     *
     * @return ChartRadios
     */
    public function ChartRadios(): ChartRadios
    {
        return ChartRadios::make();
    }

    /**
     * 创建 Checkbox 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return Checkbox
     */
    public function Checkbox($name = '', $label = ''): Checkbox
    {
        $instance = Checkbox::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Checkboxes 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return Checkboxes
     */
    public function Checkboxes($name = '', $label = ''): Checkboxes
    {
        $instance = Checkboxes::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Code 渲染器实例.
     *
     * @return Code
     */
    public function Code(): Code
    {
        return Code::make();
    }

    /**
     * 创建 Collapse 渲染器实例.
     *
     * @return Collapse
     */
    public function Collapse(): Collapse
    {
        return Collapse::make();
    }

    /**
     * 创建 CollapseGroup 渲染器实例.
     *
     * @return CollapseGroup
     */
    public function CollapseGroup(): CollapseGroup
    {
        return CollapseGroup::make();
    }

    /**
     * 创建 Color 渲染器实例.
     *
     * @return Color
     */
    public function Color(): Color
    {
        return Color::make();
    }

    /**
     * 创建 Column 渲染器实例.
     *
     * @return Column
     */
    public function Column(): Column
    {
        return Column::make();
    }

    /**
     * 创建 ComboCondition 渲染器实例.
     *
     * @return ComboCondition
     */
    public function ComboCondition(): ComboCondition
    {
        return ComboCondition::make();
    }

    /**
     * 创建 Combo 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return Combo
     */
    public function Combo($name = '', $label = ''): Combo
    {
        $instance = Combo::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Component 渲染器实例.
     *
     * @return Component
     */
    public function Component(): Component
    {
        return Component::make();
    }

    /**
     * 创建 ConditionBuilder 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return ConditionBuilder
     */
    public function ConditionBuilder($name = '', $label = ''): ConditionBuilder
    {
        $instance = ConditionBuilder::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 ConditionGroupValue 渲染器实例.
     *
     * @return ConditionGroupValue
     */
    public function ConditionGroupValue(): ConditionGroupValue
    {
        return ConditionGroupValue::make();
    }

    /**
     * 创建 Container 渲染器实例.
     *
     * @return Container
     */
    public function Container(): Container
    {
        return Container::make();
    }

    /**
     * 创建 CopyAction 渲染器实例.
     *
     * @return CopyAction
     */
    public function CopyAction(): CopyAction
    {
        return CopyAction::make();
    }

    /**
     * 创建 Custom 渲染器实例.
     *
     * @return Custom
     */
    public function Custom(): Custom
    {
        return Custom::make();
    }

    /**
     * 创建 Date 渲染器实例.
     *
     * @return Date
     */
    public function Date(): Date
    {
        return Date::make();
    }

    /**
     * 创建 InputDate 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return InputDate
     */
    public function InputDate($name = '', $label = ''): InputDate
    {
        $instance = InputDate::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 DateRange 渲染器实例.
     *
     * @return DateRange
     */
    public function DateRange(): DateRange
    {
        return DateRange::make();
    }

    /**
     * 创建 InputDateRange 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return InputDateRange
     */
    public function InputDateRange($name = '', $label = ''): InputDateRange
    {
        $instance = InputDateRange::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputDateTime 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return InputDateTime
     */
    public function InputDateTime($name = '', $label = ''): InputDateTime
    {
        $instance = InputDateTime::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Dialog 渲染器实例.
     *
     * @return Dialog
     */
    public function Dialog(): Dialog
    {
        return Dialog::make();
    }

    /**
     * 创建 DialogAction 渲染器实例.
     *
     * @return DialogAction
     */
    public function DialogAction(): DialogAction
    {
        return DialogAction::make();
    }

    /**
     * 创建 DiffEditor 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return DiffEditor
     */
    public function DiffEditor($name = '', $label = ''): DiffEditor
    {
        $instance = DiffEditor::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Divider 渲染器实例.
     *
     * @return Divider
     */
    public function Divider(): Divider
    {
        return Divider::make();
    }

    /**
     * 创建 Drawer 渲染器实例.
     *
     * @return Drawer
     */
    public function Drawer(): Drawer
    {
        return Drawer::make();
    }

    /**
     * 创建 DrawerAction 渲染器实例.
     *
     * @return DrawerAction
     */
    public function DrawerAction(): DrawerAction
    {
        return DrawerAction::make();
    }

    /**
     * 创建 DropdownButton 渲染器实例.
     *
     * @return DropdownButton
     */
    public function DropdownButton(): DropdownButton
    {
        return DropdownButton::make();
    }

    /**
     * 创建 Each 渲染器实例.
     *
     * @return Each
     */
    public function Each(): Each
    {
        return Each::make();
    }

    /**
     * 创建 Editor 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return Editor
     */
    public function Editor($name = '', $label = ''): Editor
    {
        $instance = Editor::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 EmailAction 渲染器实例.
     *
     * @return EmailAction
     */
    public function EmailAction(): EmailAction
    {
        return EmailAction::make();
    }

    /**
     * 创建 Expandable 渲染器实例.
     *
     * @return Expandable
     */
    public function Expandable(): Expandable
    {
        return Expandable::make();
    }

    /**
     * 创建 FeedbackDialog 渲染器实例.
     *
     * @return FeedbackDialog
     */
    public function FeedbackDialog(): FeedbackDialog
    {
        return FeedbackDialog::make();
    }

    /**
     * 创建 FieldSet 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return FieldSet
     */
    public function FieldSet($name = '', $label = ''): FieldSet
    {
        $instance = FieldSet::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputFile 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return InputFile
     */
    public function InputFile($name = '', $label = ''): InputFile
    {
        $instance = InputFile::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Flex 渲染器实例.
     *
     * @return Flex
     */
    public function Flex(): Flex
    {
        return Flex::make();
    }

    /**
     * 创建 Form 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return Form
     */
    public function Form($name = '', $label = ''): Form
    {
        $instance = Form::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Formula 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return Formula
     */
    public function Formula($name = '', $label = ''): Formula
    {
        $instance = Formula::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Grid 渲染器实例.
     *
     * @return Grid
     */
    public function Grid(): Grid
    {
        return Grid::make();
    }

    /**
     * 创建 Grid2D 渲染器实例.
     *
     * @return Grid2D
     */
    public function Grid2D(): Grid2D
    {
        return Grid2D::make();
    }

    /**
     * 创建 GridColumn 渲染器实例.
     *
     * @return GridColumn
     */
    public function GridColumn(): GridColumn
    {
        return GridColumn::make();
    }

    /**
     * 创建 GridNav 渲染器实例.
     *
     * @return GridNav
     */
    public function GridNav(): GridNav
    {
        return GridNav::make();
    }

    /**
     * 创建 Group 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return Group
     */
    public function Group($name = '', $label = ''): Group
    {
        $instance = Group::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 HBox 渲染器实例.
     *
     * @return HBox
     */
    public function HBox(): HBox
    {
        return HBox::make();
    }

    /**
     * 创建 HBoxColumn 渲染器实例.
     *
     * @return HBoxColumn
     */
    public function HBoxColumn(): HBoxColumn
    {
        return HBoxColumn::make();
    }

    /**
     * 创建 Hidden 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return Hidden
     */
    public function Hidden($name = '', $label = ''): Hidden
    {
        $instance = Hidden::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Html 渲染器实例.
     *
     * @return Html
     */
    public function Html(): Html
    {
        return Html::make();
    }

    /**
     * 创建 IFrame 渲染器实例.
     *
     * @return IFrame
     */
    public function IFrame(): IFrame
    {
        return IFrame::make();
    }

    /**
     * 创建 Icon 渲染器实例.
     *
     * @return Icon
     */
    public function Icon(): Icon
    {
        return Icon::make();
    }

    /**
     * 创建 IconChecked 渲染器实例.
     *
     * @return IconChecked
     */
    public function IconChecked(): IconChecked
    {
        return IconChecked::make();
    }

    /**
     * 创建 IconItem 渲染器实例.
     *
     * @return IconItem
     */
    public function IconItem(): IconItem
    {
        return IconItem::make();
    }

    /**
     * 创建 IconPicker 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return IconPicker
     */
    public function IconPicker($name = '', $label = ''): IconPicker
    {
        $instance = IconPicker::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Image 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return Image
     */
    public function Image($name = '', $label = ''): Image
    {
        $instance = Image::make();
        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputImage 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return InputImage
     */
    public function InputImage($name = '', $label = ''): InputImage
    {
        $instance = InputImage::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 ImageToolbarAction 渲染器实例.
     *
     * @return ImageToolbarAction
     */
    public function ImageToolbarAction(): ImageToolbarAction
    {
        return ImageToolbarAction::make();
    }

    /**
     * 创建 Images 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return Images
     */
    public function Images($name = '', $label = ''): Images
    {
        $instance = Images::make();
        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputCity 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return InputCity
     */
    public function InputCity($name = '', $label = ''): InputCity
    {
        $instance = InputCity::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputColor 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return InputColor
     */
    public function InputColor($name = '', $label = ''): InputColor
    {
        $instance = InputColor::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputDatetimeRange 渲染器实例.
     *
     * @return InputDatetimeRange
     */
    public function InputDatetimeRange(): InputDatetimeRange
    {
        return InputDatetimeRange::make();
    }

    /**
     * 创建 InputExcel 渲染器实例.
     *
     * @return InputExcel
     */
    public function InputExcel(): InputExcel
    {
        return InputExcel::make();
    }

    /**
     * 创建 InputGroup 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return InputGroup
     */
    public function InputGroup($name = '', $label = ''): InputGroup
    {
        $instance = InputGroup::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputKV 渲染器实例.
     *
     * @return InputKV
     */
    public function InputKV(): InputKV
    {
        return InputKV::make();
    }

    /**
     * 创建 InputKVS 渲染器实例.
     *
     * @return InputKVS
     */
    public function InputKVS(): InputKVS
    {
        return InputKVS::make();
    }

    /**
     * 创建 InputSignature 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return InputSignature
     */
    public function InputSignature($name = '', $label = ''): InputSignature
    {
        $instance = InputSignature::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputTimeRange 渲染器实例.
     *
     * @return InputTimeRange
     */
    public function InputTimeRange(): InputTimeRange
    {
        return InputTimeRange::make();
    }

    /**
     * 创建 InputYearRange 渲染器实例.
     *
     * @return InputYearRange
     */
    public function InputYearRange(): InputYearRange
    {
        return InputYearRange::make();
    }

    /**
     * 创建 InputPassword 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return InputPassword
     */
    public function InputPassword($name = '', $label = ''): InputPassword
    {
        $instance = InputPassword::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }
        return $instance;
    }

    /**
     * 创建 JSONSchemaEditor 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return JSONSchemaEditor
     */
    public function JSONSchemaEditor($name = '', $label = ''): JSONSchemaEditor
    {
        $instance = JSONSchemaEditor::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Json 渲染器实例.
     *
     * @return Json
     */
    public function Json(): Json
    {
        return Json::make();
    }

    /**
     * 创建 Link 渲染器实例.
     *
     * @return Link
     */
    public function Link(): Link
    {
        return Link::make();
    }

    /**
     * 创建 LinkAction 渲染器实例.
     *
     * @return LinkAction
     */
    public function LinkAction(): LinkAction
    {
        return LinkAction::make();
    }

    /**
     * 创建 ListBodyField 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return ListBodyField
     */
    public function ListBodyField($name = '', $label = ''): ListBodyField
    {
        $instance = ListBodyField::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 ListSelect 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return ListSelect
     */
    public function ListSelect($name = '', $label = ''): ListSelect
    {
        $instance = ListSelect::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 ListItem 渲染器实例.
     *
     * @return ListItem
     */
    public function ListItem(): ListItem
    {
        return ListItem::make();
    }

    /**
     * 创建 ListRenderer 渲染器实例.
     *
     * @return ListRenderer
     */
    public function ListRenderer(): ListRenderer
    {
        return ListRenderer::make();
    }

    /**
     * 创建 ListenerAction 渲染器实例.
     *
     * @return ListenerAction
     */
    public function ListenerAction(): ListenerAction
    {
        return ListenerAction::make();
    }

    /**
     * 创建 LocationPicker 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return LocationPicker
     */
    public function LocationPicker($name = '', $label = ''): LocationPicker
    {
        $instance = LocationPicker::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Log 渲染器实例.
     *
     * @return Log
     */
    public function Log(): Log
    {
        return Log::make();
    }

    /**
     * 创建 Mapping 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return Mapping
     */
    public function Mapping($name = '', $label = ''): Mapping
    {
        $instance = Mapping::make();
        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Markdown 渲染器实例.
     *
     * @return Markdown
     */
    public function Markdown(): Markdown
    {
        return Markdown::make();
    }

    /**
     * 创建 MatrixCheckboxes 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return MatrixCheckboxes
     */
    public function MatrixCheckboxes($name = '', $label = ''): MatrixCheckboxes
    {
        $instance = MatrixCheckboxes::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputMonth 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return InputMonth
     */
    public function InputMonth($name = '', $label = ''): InputMonth
    {
        $instance = InputMonth::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputMonthRange 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return InputMonthRange
     */
    public function InputMonthRange($name = '', $label = ''): InputMonthRange
    {
        $instance = InputMonthRange::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 MultilineText 渲染器实例.
     *
     * @return MultilineText
     */
    public function MultilineText(): MultilineText
    {
        return MultilineText::make();
    }

    /**
     * 创建 Nav 渲染器实例.
     *
     * @return Nav
     */
    public function Nav(): Nav
    {
        return Nav::make();
    }

    /**
     * 创建 NavItem 渲染器实例.
     *
     * @return NavItem
     */
    public function NavItem(): NavItem
    {
        return NavItem::make();
    }

    /**
     * 创建 NavOverflow 渲染器实例.
     *
     * @return NavOverflow
     */
    public function NavOverflow(): NavOverflow
    {
        return NavOverflow::make();
    }

    /**
     * 创建 NestedSelect 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return NestedSelect
     */
    public function NestedSelect($name = '', $label = ''): NestedSelect
    {
        $instance = NestedSelect::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputNumber 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return InputNumber
     */
    public function InputNumber($name = '', $label = ''): InputNumber
    {
        $instance = InputNumber::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Operation 渲染器实例.
     *
     * @return Operation
     */
    public function Operation(): Operation
    {
        return Operation::make();
    }

    /**
     * 创建 Option 渲染器实例.
     *
     * @return Option
     */
    public function Option(): Option
    {
        return Option::make();
    }

    /**
     * 创建 Options 渲染器实例.
     *
     * @return Options
     */
    public function Options(): Options
    {
        return Options::make();
    }

    /**
     * 创建 OtherAction 渲染器实例.
     *
     * @return OtherAction
     */
    public function OtherAction(): OtherAction
    {
        return OtherAction::make();
    }

    /**
     * 创建 Page 渲染器实例.
     *
     * @return Page
     */
    public function Page(): Page
    {
        return Page::make();
    }

    /**
     * 创建 Pagination 渲染器实例.
     *
     * @return Pagination
     */
    public function Pagination(): Pagination
    {
        return Pagination::make();
    }

    /**
     * 创建 PaginationWrapper 渲染器实例.
     *
     * @return PaginationWrapper
     */
    public function PaginationWrapper(): PaginationWrapper
    {
        return PaginationWrapper::make();
    }

    /**
     * 创建 Panel 渲染器实例.
     *
     * @return Panel
     */
    public function Panel(): Panel
    {
        return Panel::make();
    }

    /**
     * 创建 Password 渲染器实例.
     *
     * @return Password
     */
    public function Password(): Password
    {
        return Password::make();
    }

    /**
     * 创建 Picker 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return Picker
     */
    public function Picker($name = '', $label = ''): Picker
    {
        $instance = Picker::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Plain 渲染器实例.
     *
     * @return Plain
     */
    public function Plain(): Plain
    {
        return Plain::make();
    }

    /**
     * 创建 Portlet 渲染器实例.
     *
     * @return Portlet
     */
    public function Portlet(): Portlet
    {
        return Portlet::make();
    }

    /**
     * 创建 PortletTab 渲染器实例.
     *
     * @return PortletTab
     */
    public function PortletTab(): PortletTab
    {
        return PortletTab::make();
    }

    /**
     * 创建 Progress 渲染器实例.
     *
     * @return Progress
     */
    public function Progress(): Progress
    {
        return Progress::make();
    }

    /**
     * 创建 Property 渲染器实例.
     *
     * @return Property
     */
    public function Property(): Property
    {
        return Property::make();
    }

    /**
     * 创建 QRCode 渲染器实例.
     *
     * @return QRCode
     */
    public function QRCode(): QRCode
    {
        return QRCode::make();
    }

    /**
     * 创建 QRCodeImageSettings 渲染器实例.
     *
     * @return QRCodeImageSettings
     */
    public function QRCodeImageSettings(): QRCodeImageSettings
    {
        return QRCodeImageSettings::make();
    }

    /**
     * 创建 InputQuarter 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return InputQuarter
     */
    public function InputQuarter($name = '', $label = ''): InputQuarter
    {
        $instance = InputQuarter::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputQuarterRange 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return InputQuarterRange
     */
    public function InputQuarterRange($name = '', $label = ''): InputQuarterRange
    {
        $instance = InputQuarterRange::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Radio 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return Radio
     */
    public function Radio($name = '', $label = ''): Radio
    {
        $instance = Radio::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Radios 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return Radios
     */
    public function Radios($name = '', $label = ''): Radios
    {
        $instance = Radios::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputRange 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return InputRange
     */
    public function InputRange($name = '', $label = ''): InputRange
    {
        $instance = InputRange::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputRating 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return InputRating
     */
    public function InputRating($name = '', $label = ''): InputRating
    {
        $instance = InputRating::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 ReloadAction 渲染器实例.
     *
     * @return ReloadAction
     */
    public function ReloadAction(): ReloadAction
    {
        return ReloadAction::make();
    }

    /**
     * 创建 Remark 渲染器实例.
     *
     * @return Remark
     */
    public function Remark(): Remark
    {
        return Remark::make();
    }

    /**
     * 创建 InputRepeat 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return InputRepeat
     */
    public function InputRepeat($name = '', $label = ''): InputRepeat
    {
        $instance = InputRepeat::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputRichText 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return InputRichText
     */
    public function InputRichText($name = '', $label = ''): InputRichText
    {
        $instance = InputRichText::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 RowSelection 渲染器实例.
     *
     * @return RowSelection
     */
    public function RowSelection(): RowSelection
    {
        return RowSelection::make();
    }

    /**
     * 创建 RowSelectionOptions 渲染器实例.
     *
     * @return RowSelectionOptions
     */
    public function RowSelectionOptions(): RowSelectionOptions
    {
        return RowSelectionOptions::make();
    }

    /**
     * 创建 SchemaApi 渲染器实例.
     *
     * @return SchemaApi
     */
    public function SchemaApi(): SchemaApi
    {
        return SchemaApi::make();
    }

    /**
     * 创建 SchemaCopyable 渲染器实例.
     *
     * @return SchemaCopyable
     */
    public function SchemaCopyable(): SchemaCopyable
    {
        return SchemaCopyable::make();
    }

    /**
     * 创建 SchemaMessage 渲染器实例.
     *
     * @return SchemaMessage
     */
    public function SchemaMessage(): SchemaMessage
    {
        return SchemaMessage::make();
    }

    /**
     * 创建 SchemaPopOver 渲染器实例.
     *
     * @return SchemaPopOver
     */
    public function SchemaPopOver(): SchemaPopOver
    {
        return SchemaPopOver::make();
    }

    /**
     * 创建 SearchBox 渲染器实例.
     *
     * @return SearchBox
     */
    public function SearchBox(): SearchBox
    {
        return SearchBox::make();
    }

    /**
     * 创建 Select 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return Select
     */
    public function Select($name = '', $label = ''): Select
    {
        $instance = Select::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Service 渲染器实例.
     *
     * @return Service
     */
    public function Service(): Service
    {
        return Service::make();
    }

    /**
     * 创建 SparkLine 渲染器实例.
     *
     * @return SparkLine
     */
    public function SparkLine(): SparkLine
    {
        return SparkLine::make();
    }

    /**
     * 创建 Spinner 渲染器实例.
     *
     * @return Spinner
     */
    public function Spinner(): Spinner
    {
        return Spinner::make();
    }

    /**
     * 创建 State 渲染器实例.
     *
     * @return State
     */
    public function State(): State
    {
        return State::make();
    }

    /**
     * 创建 StaticExactControl 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return StaticExactControl
     */
    public function Static($name = '', $label = ''): StaticExactControl
    {
        $instance = StaticExactControl::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Status 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return Status
     */
    public function Status($name = '', $label = ''): Status
    {
        $instance = Status::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Step 渲染器实例.
     *
     * @return Step
     */
    public function Step(): Step
    {
        return Step::make();
    }

    /**
     * 创建 Steps 渲染器实例.
     *
     * @return Steps
     */
    public function Steps(): Steps
    {
        return Steps::make();
    }

    /**
     * 创建 InputSubForm 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return InputSubForm
     */
    public function InputSubForm($name = '', $label = ''): InputSubForm
    {
        $instance = InputSubForm::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 CustomSvgIcon 渲染器实例.
     *
     * @return CustomSvgIcon
     */
    public function CustomSvgIcon(): CustomSvgIcon
    {
        return CustomSvgIcon::make();
    }

    /**
     * 创建 SwitchContainer 渲染器实例.
     *
     * @return SwitchContainer
     */
    public function SwitchContainer(): SwitchContainer
    {
        return SwitchContainer::make();
    }

    /**
     * 创建 Switch 渲染器实例.
     *
     * @param string      $name  字段名
     * @param string      $label 标签
     * @return SwitchClass
     */
    public function Switch($name = '', $label = ''): SwitchClass
    {
        $instance = SwitchClass::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Tab 渲染器实例.
     *
     * @return Tab
     */
    public function Tab(): Tab
    {
        return Tab::make();
    }

    /**
     * 创建 Table 渲染器实例.
     *
     * @return Table
     */
    public function Table(): Table
    {
        return Table::make();
    }

    /**
     * 创建 TableColumn 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return TableColumn
     */
    public function TableColumn($name = '', $label = ''): TableColumn
    {
        $instance = TableColumn::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 inputTable 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return inputTable
     */
    public function inputTable($name = '', $label = ''): inputTable
    {
        $instance = inputTable::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Table2 渲染器实例.
     *
     * @return Table2
     */
    public function Table2(): Table2
    {
        return Table2::make();
    }

    /**
     * 创建 TableView 渲染器实例.
     *
     * @return TableView
     */
    public function TableView(): TableView
    {
        return TableView::make();
    }

    /**
     * 创建 Tabs 渲染器实例.
     *
     * @return Tabs
     */
    public function Tabs(): Tabs
    {
        return Tabs::make();
    }

    /**
     * 创建 TabsTransfer 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return TabsTransfer
     */
    public function TabsTransfer($name = '', $label = ''): TabsTransfer
    {
        $instance = TabsTransfer::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 TabsTransferPicker 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return TabsTransferPicker
     */
    public function TabsTransferPicker($name = '', $label = ''): TabsTransferPicker
    {
        $instance = TabsTransferPicker::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Tag 渲染器实例.
     *
     * @return Tag
     */
    public function Tag(): Tag
    {
        return Tag::make();
    }

    /**
     * 创建 InputTag 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return InputTag
     */
    public function InputTag($name = '', $label = ''): InputTag
    {
        $instance = InputTag::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Tasks 渲染器实例.
     *
     * @return Tasks
     */
    public function Tasks(): Tasks
    {
        return Tasks::make();
    }

    /**
     * 创建 InputText 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return InputText
     */
    public function InputText($name = '', $label = ''): InputText
    {
        $instance = InputText::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Textarea 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return Textarea
     */
    public function Textarea($name = '', $label = ''): Textarea
    {
        $instance = Textarea::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputTime 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return InputTime
     */
    public function InputTime($name = '', $label = ''): InputTime
    {
        $instance = InputTime::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Timeline 渲染器实例.
     *
     * @return Timeline
     */
    public function Timeline(): Timeline
    {
        return Timeline::make();
    }

    /**
     * 创建 TimelineItem 渲染器实例.
     *
     * @return TimelineItem
     */
    public function TimelineItem(): TimelineItem
    {
        return TimelineItem::make();
    }

    /**
     * 创建 Toast 渲染器实例.
     *
     * @return Toast
     */
    public function Toast(): Toast
    {
        return Toast::make();
    }

    /**
     * 创建 ToastAction 渲染器实例.
     *
     * @return ToastAction
     */
    public function ToastAction(): ToastAction
    {
        return ToastAction::make();
    }

    /**
     * 创建 TooltipWrapper 渲染器实例.
     *
     * @return TooltipWrapper
     */
    public function TooltipWrapper(): TooltipWrapper
    {
        return TooltipWrapper::make();
    }

    /**
     * 创建 Tpl 渲染器实例.
     *
     * @return Tpl
     */
    public function Tpl(): Tpl
    {
        return Tpl::make();
    }

    /**
     * 创建 Transfer 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return Transfer
     */
    public function Transfer($name = '', $label = ''): Transfer
    {
        $instance = Transfer::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 TransferPicker 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return TransferPicker
     */
    public function TransferPicker($name = '', $label = ''): TransferPicker
    {
        $instance = TransferPicker::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 InputTree 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return InputTree
     */
    public function InputTree($name = '', $label = ''): InputTree
    {
        $instance = InputTree::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 TreeSelect 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return TreeSelect
     */
    public function TreeSelect($name = '', $label = ''): TreeSelect
    {
        $instance = TreeSelect::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 UUID 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return UUID
     */
    public function UUID($name = '', $label = ''): UUID
    {
        $instance = UUID::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 UrlAction 渲染器实例.
     *
     * @return UrlAction
     */
    public function UrlAction(): UrlAction
    {
        return UrlAction::make();
    }

    /**
     * 创建 UserSelect 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return UserSelect
     */
    public function UserSelect($name = '', $label = ''): UserSelect
    {
        $instance = UserSelect::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 VBox 渲染器实例.
     *
     * @return VBox
     */
    public function VBox(): VBox
    {
        return VBox::make();
    }

//    public function VanillaAction()
//    {
//        return Button::make();
//    }

    /**
     * 创建 Video 渲染器实例.
     *
     * @return Video
     */
    public function Video(): Video
    {
        return Video::make();
    }

    /**
     * 创建 CustomWangEditor 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return CustomWangEditor
     */
    public function CustomWangEditor($name = '', $label = ''): CustomWangEditor
    {
        $instance = CustomWangEditor::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 CustomWatermark 渲染器实例.
     *
     * @return CustomWatermark
     */
    public function CustomWatermark(): CustomWatermark
    {
        return CustomWatermark::make();
    }

    /**
     * 创建 WebComponent 渲染器实例.
     *
     * @return WebComponent
     */
    public function WebComponent(): WebComponent
    {
        return WebComponent::make();
    }

    /**
     * 创建 Wizard 渲染器实例.
     *
     * @return Wizard
     */
    public function Wizard(): Wizard
    {
        return Wizard::make();
    }

    /**
     * 创建 WizardStep 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return WizardStep
     */
    public function WizardStep($name = '', $label = ''): WizardStep
    {
        $instance = WizardStep::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    /**
     * 创建 Words 渲染器实例.
     *
     * @return Words
     */
    public function Words(): Words
    {
        return Words::make();
    }

    /**
     * 创建 Wrapper 渲染器实例.
     *
     * @return Wrapper
     */
    public function Wrapper(): Wrapper
    {
        return Wrapper::make();
    }

    /**
     * 创建 InputYear 渲染器实例.
     *
     * @param string $name  字段名
     * @param string $label 标签
     * @return InputYear
     */
    public function InputYear($name = '', $label = ''): InputYear
    {
        $instance = InputYear::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

}
