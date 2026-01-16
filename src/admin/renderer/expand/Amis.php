<?php

namespace warm\admin\renderer\expand;

/**
 * Amis 构建器扩展类
 */
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
     * @return \warm\admin\renderer\Action
     */
    public function Action(): \warm\admin\renderer\Action
    {
        return \warm\admin\renderer\Action::make();
    }

    /**
     * @return \warm\admin\renderer\expand\AjaxAction
     */
    public function AjaxAction(): \warm\admin\renderer\expand\AjaxAction
    {
        return \warm\admin\renderer\expand\AjaxAction::make();
    }

    /**
     * @return \warm\admin\renderer\Alert
     */
    public function Alert(): \warm\admin\renderer\Alert
    {
        return \warm\admin\renderer\Alert::make();
    }

    /**
     * @return \warm\admin\renderer\AnchorNav
     */
    public function AnchorNav(): \warm\admin\renderer\AnchorNav
    {
        return \warm\admin\renderer\AnchorNav::make();
    }

    /**
     * @return \warm\admin\renderer\App
     */
    public function App(): \warm\admin\renderer\App
    {
        return \warm\admin\renderer\App::make();
    }

    /**
     * @return \warm\admin\renderer\Audio
     */
    public function Audio(): \warm\admin\renderer\Audio
    {
        return \warm\admin\renderer\Audio::make();
    }

    /**
     * @return \warm\admin\renderer\Avatar
     */
    public function Avatar(): \warm\admin\renderer\Avatar
    {
        return \warm\admin\renderer\Avatar::make();
    }

    /**
     * @return \warm\admin\renderer\Badge
     */
    public function Badge(): \warm\admin\renderer\Badge
    {
        return \warm\admin\renderer\Badge::make();
    }

    /**
     * @return \warm\admin\renderer\Barcode
     */
    public function Barcode(): \warm\admin\renderer\Barcode
    {
        return \warm\admin\renderer\Barcode::make();
    }

    /**
     * @return \warm\admin\renderer\Breadcrumb
     */
    public function Breadcrumb(): \warm\admin\renderer\Breadcrumb
    {
        return \warm\admin\renderer\Breadcrumb::make();
    }

    /**
     * @return \warm\admin\renderer\Button
     */
    public function Button(): \warm\admin\renderer\Button
    {
        return \warm\admin\renderer\Button::make();
    }

    /**
     * @return \warm\admin\renderer\ButtonGroup
     */
    public function ButtonGroup(): \warm\admin\renderer\ButtonGroup
    {
        return \warm\admin\renderer\ButtonGroup::make();
    }

    /**
     * @return \warm\admin\renderer\form\ButtonGroupSelect
     */
    public function ButtonGroupSelect(): \warm\admin\renderer\form\ButtonGroupSelect
    {
        return \warm\admin\renderer\form\ButtonGroupSelect::make();
    }

    /**
     * @return \warm\admin\renderer\form\ButtonToolbar
     */
    public function ButtonToolbar(): \warm\admin\renderer\form\ButtonToolbar
    {
        return \warm\admin\renderer\form\ButtonToolbar::make();
    }

    /**
     * @return \warm\admin\renderer\Calendar
     */
    public function Calendar(): \warm\admin\renderer\Calendar
    {
        return \warm\admin\renderer\Calendar::make();
    }

    /**
     * @return \warm\admin\renderer\Card
     */
    public function Card(): \warm\admin\renderer\Card
    {
        return \warm\admin\renderer\Card::make();
    }

    /**
     * @return \warm\admin\renderer\Card2
     */
    public function Card2(): \warm\admin\renderer\Card2
    {
        return \warm\admin\renderer\Card2::make();
    }

    /**
     * @return \warm\admin\renderer\Cards
     */
    public function Cards(): \warm\admin\renderer\Cards
    {
        return \warm\admin\renderer\Cards::make();
    }

    /**
     * @return \warm\admin\renderer\Carousel
     */
    public function Carousel(): \warm\admin\renderer\Carousel
    {
        return \warm\admin\renderer\Carousel::make();
    }

    /**
     * @return \warm\admin\renderer\form\ChainSelect
     */
    public function ChainSelect(): \warm\admin\renderer\form\ChainSelect
    {
        return \warm\admin\renderer\form\ChainSelect::make();
    }

    /**
     * @return \warm\admin\renderer\Chart
     */
    public function Chart(): \warm\admin\renderer\Chart
    {
        return \warm\admin\renderer\Chart::make();
    }

    /**
     * @return \warm\admin\renderer\form\ChartRadios
     */
    public function ChartRadios(): \warm\admin\renderer\form\ChartRadios
    {
        return \warm\admin\renderer\form\ChartRadios::make();
    }

    /**
     * @return \warm\admin\renderer\form\Checkbox
     */
    public function Checkbox(): \warm\admin\renderer\form\Checkbox
    {
        return \warm\admin\renderer\form\Checkbox::make();
    }

    /**
     * @return \warm\admin\renderer\form\Checkboxes
     */
    public function Checkboxes(): \warm\admin\renderer\form\Checkboxes
    {
        return \warm\admin\renderer\form\Checkboxes::make();
    }

    /**
     * @return \warm\admin\renderer\Code
     */
    public function Code(): \warm\admin\renderer\Code
    {
        return \warm\admin\renderer\Code::make();
    }

    /**
     * @return \warm\admin\renderer\Collapse
     */
    public function Collapse(): \warm\admin\renderer\Collapse
    {
        return \warm\admin\renderer\Collapse::make();
    }

    /**
     * @return \warm\admin\renderer\Color
     */
    public function Color(): \warm\admin\renderer\Color
    {
        return \warm\admin\renderer\Color::make();
    }

    /**
     * @return \warm\admin\renderer\form\Combo
     */
    public function Combo(): \warm\admin\renderer\form\Combo
    {
        return \warm\admin\renderer\form\Combo::make();
    }

    /**
     * @return \warm\admin\renderer\expand\Component
     */
    public function Component(): \warm\admin\renderer\expand\Component
    {
        return \warm\admin\renderer\expand\Component::make();
    }

    /**
     * @return \warm\admin\renderer\form\ConditionBuilder
     */
    public function ConditionBuilder(): \warm\admin\renderer\form\ConditionBuilder
    {
        return \warm\admin\renderer\form\ConditionBuilder::make();
    }

    /**
     * @return \warm\admin\renderer\Container
     */
    public function Container(): \warm\admin\renderer\Container
    {
        return \warm\admin\renderer\Container::make();
    }

    /**
     * @return \warm\admin\renderer\form\Control
     */
    public function Control(): \warm\admin\renderer\form\Control
    {
        return \warm\admin\renderer\form\Control::make();
    }

    /**
     * @return \warm\admin\renderer\expand\CopyAction
     */
    public function CopyAction(): \warm\admin\renderer\expand\CopyAction
    {
        return \warm\admin\renderer\expand\CopyAction::make();
    }

    /**
     * @return \warm\admin\renderer\Crud
     */
    public function Crud(): \warm\admin\renderer\Crud
    {
        return \warm\admin\renderer\Crud::make();
    }

    /**
     * @return \warm\admin\renderer\Custom
     */
    public function Custom(): \warm\admin\renderer\Custom
    {
        return \warm\admin\renderer\Custom::make();
    }

    /**
     * @return \warm\admin\renderer\expand\CustomSvgIcon
     */
    public function CustomSvgIcon(): \warm\admin\renderer\expand\CustomSvgIcon
    {
        return \warm\admin\renderer\expand\CustomSvgIcon::make();
    }

    /**
     * @return \warm\admin\renderer\expand\CustomWangEditor
     */
    public function CustomWangEditor(): \warm\admin\renderer\expand\CustomWangEditor
    {
        return \warm\admin\renderer\expand\CustomWangEditor::make();
    }

    /**
     * @return \warm\admin\renderer\expand\CustomWatermark
     */
    public function CustomWatermark(): \warm\admin\renderer\expand\CustomWatermark
    {
        return \warm\admin\renderer\expand\CustomWatermark::make();
    }

    /**
     * @return \warm\admin\renderer\Date
     */
    public function Date(): \warm\admin\renderer\Date
    {
        return \warm\admin\renderer\Date::make();
    }

    /**
     * @return \warm\admin\renderer\Dialog
     */
    public function Dialog(): \warm\admin\renderer\Dialog
    {
        return \warm\admin\renderer\Dialog::make();
    }

    /**
     * @return \warm\admin\renderer\expand\DialogAction
     */
    public function DialogAction(): \warm\admin\renderer\expand\DialogAction
    {
        return \warm\admin\renderer\expand\DialogAction::make();
    }

    /**
     * @return \warm\admin\renderer\form\DiffEditor
     */
    public function DiffEditor(): \warm\admin\renderer\form\DiffEditor
    {
        return \warm\admin\renderer\form\DiffEditor::make();
    }

    /**
     * @return \warm\admin\renderer\Divider
     */
    public function Divider(): \warm\admin\renderer\Divider
    {
        return \warm\admin\renderer\Divider::make();
    }

    /**
     * @return \warm\admin\renderer\Drawer
     */
    public function Drawer(): \warm\admin\renderer\Drawer
    {
        return \warm\admin\renderer\Drawer::make();
    }

    /**
     * @return \warm\admin\renderer\DropdownButton
     */
    public function DropdownButton(): \warm\admin\renderer\DropdownButton
    {
        return \warm\admin\renderer\DropdownButton::make();
    }

    /**
     * @return \warm\admin\renderer\Each
     */
    public function Each(): \warm\admin\renderer\Each
    {
        return \warm\admin\renderer\Each::make();
    }

    /**
     * @return \warm\admin\renderer\form\Editor
     */
    public function Editor(): \warm\admin\renderer\form\Editor
    {
        return \warm\admin\renderer\form\Editor::make();
    }

    /**
     * @return \warm\admin\renderer\expand\EmailAction
     */
    public function EmailAction(): \warm\admin\renderer\expand\EmailAction
    {
        return \warm\admin\renderer\expand\EmailAction::make();
    }

    /**
     * @return \warm\admin\renderer\form\Fieldset
     */
    public function Fieldset(): \warm\admin\renderer\form\Fieldset
    {
        return \warm\admin\renderer\form\Fieldset::make();
    }

    /**
     * @return \warm\admin\renderer\Flex
     */
    public function Flex(): \warm\admin\renderer\Flex
    {
        return \warm\admin\renderer\Flex::make();
    }

    /**
     * @return \warm\admin\renderer\form\Formitem
     */
    public function Formitem(): \warm\admin\renderer\form\Formitem
    {
        return \warm\admin\renderer\form\Formitem::make();
    }

    /**
     * @return \warm\admin\renderer\form\Formula
     */
    public function Formula(): \warm\admin\renderer\form\Formula
    {
        return \warm\admin\renderer\form\Formula::make();
    }

    /**
     * @return \warm\admin\renderer\Grid
     */
    public function Grid(): \warm\admin\renderer\Grid
    {
        return \warm\admin\renderer\Grid::make();
    }

    /**
     * @return \warm\admin\renderer\Grid2d
     */
    public function Grid2d(): \warm\admin\renderer\Grid2d
    {
        return \warm\admin\renderer\Grid2d::make();
    }

    /**
     * @return \warm\admin\renderer\GridNav
     */
    public function GridNav(): \warm\admin\renderer\GridNav
    {
        return \warm\admin\renderer\GridNav::make();
    }

    /**
     * @return \warm\admin\renderer\form\Group
     */
    public function Group(): \warm\admin\renderer\form\Group
    {
        return \warm\admin\renderer\form\Group::make();
    }

    /**
     * @return \warm\admin\renderer\Hbox
     */
    public function Hbox(): \warm\admin\renderer\Hbox
    {
        return \warm\admin\renderer\Hbox::make();
    }

    /**
     * @return \warm\admin\renderer\form\Hidden
     */
    public function Hidden(): \warm\admin\renderer\form\Hidden
    {
        return \warm\admin\renderer\form\Hidden::make();
    }

    /**
     * @return \warm\admin\renderer\Html
     */
    public function Html(): \warm\admin\renderer\Html
    {
        return \warm\admin\renderer\Html::make();
    }

    /**
     * @return \warm\admin\renderer\Icon
     */
    public function Icon(): \warm\admin\renderer\Icon
    {
        return \warm\admin\renderer\Icon::make();
    }

    /**
     * @return \warm\admin\renderer\Iframe
     */
    public function Iframe(): \warm\admin\renderer\Iframe
    {
        return \warm\admin\renderer\Iframe::make();
    }

    /**
     * @return \warm\admin\renderer\Image
     */
    public function Image(): \warm\admin\renderer\Image
    {
        return \warm\admin\renderer\Image::make();
    }

    /**
     * @return \warm\admin\renderer\expand\ImageToolbarAction
     */
    public function ImageToolbarAction(): \warm\admin\renderer\expand\ImageToolbarAction
    {
        return \warm\admin\renderer\expand\ImageToolbarAction::make();
    }

    /**
     * @return \warm\admin\renderer\Images
     */
    public function Images(): \warm\admin\renderer\Images
    {
        return \warm\admin\renderer\Images::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputArray
     */
    public function InputArray(): \warm\admin\renderer\form\InputArray
    {
        return \warm\admin\renderer\form\InputArray::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputCity
     */
    public function InputCity(): \warm\admin\renderer\form\InputCity
    {
        return \warm\admin\renderer\form\InputCity::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputColor
     */
    public function InputColor(): \warm\admin\renderer\form\InputColor
    {
        return \warm\admin\renderer\form\InputColor::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputDate
     */
    public function InputDate(): \warm\admin\renderer\form\InputDate
    {
        return \warm\admin\renderer\form\InputDate::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputDateRange
     */
    public function InputDateRange(): \warm\admin\renderer\form\InputDateRange
    {
        return \warm\admin\renderer\form\InputDateRange::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputDatetime
     */
    public function InputDatetime(): \warm\admin\renderer\form\InputDatetime
    {
        return \warm\admin\renderer\form\InputDatetime::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputDatetimeRange
     */
    public function InputDatetimeRange(): \warm\admin\renderer\form\InputDatetimeRange
    {
        return \warm\admin\renderer\form\InputDatetimeRange::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputExcel
     */
    public function InputExcel(): \warm\admin\renderer\form\InputExcel
    {
        return \warm\admin\renderer\form\InputExcel::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputFile
     */
    public function InputFile(): \warm\admin\renderer\form\InputFile
    {
        return \warm\admin\renderer\form\InputFile::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputFormula
     */
    public function InputFormula(): \warm\admin\renderer\form\InputFormula
    {
        return \warm\admin\renderer\form\InputFormula::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputGroup
     */
    public function InputGroup(): \warm\admin\renderer\form\InputGroup
    {
        return \warm\admin\renderer\form\InputGroup::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputImage
     */
    public function InputImage(): \warm\admin\renderer\form\InputImage
    {
        return \warm\admin\renderer\form\InputImage::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputKv
     */
    public function InputKv(): \warm\admin\renderer\form\InputKv
    {
        return \warm\admin\renderer\form\InputKv::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputKvs
     */
    public function InputKvs(): \warm\admin\renderer\form\InputKvs
    {
        return \warm\admin\renderer\form\InputKvs::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputMonth
     */
    public function InputMonth(): \warm\admin\renderer\form\InputMonth
    {
        return \warm\admin\renderer\form\InputMonth::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputMonthRange
     */
    public function InputMonthRange(): \warm\admin\renderer\form\InputMonthRange
    {
        return \warm\admin\renderer\form\InputMonthRange::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputNumber
     */
    public function InputNumber(): \warm\admin\renderer\form\InputNumber
    {
        return \warm\admin\renderer\form\InputNumber::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputPassword
     */
    public function InputPassword(): \warm\admin\renderer\form\InputPassword
    {
        return \warm\admin\renderer\form\InputPassword::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputQuarter
     */
    public function InputQuarter(): \warm\admin\renderer\form\InputQuarter
    {
        return \warm\admin\renderer\form\InputQuarter::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputQuarterRange
     */
    public function InputQuarterRange(): \warm\admin\renderer\form\InputQuarterRange
    {
        return \warm\admin\renderer\form\InputQuarterRange::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputRange
     */
    public function InputRange(): \warm\admin\renderer\form\InputRange
    {
        return \warm\admin\renderer\form\InputRange::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputRating
     */
    public function InputRating(): \warm\admin\renderer\form\InputRating
    {
        return \warm\admin\renderer\form\InputRating::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputRepeat
     */
    public function InputRepeat(): \warm\admin\renderer\form\InputRepeat
    {
        return \warm\admin\renderer\form\InputRepeat::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputRichText
     */
    public function InputRichText(): \warm\admin\renderer\form\InputRichText
    {
        return \warm\admin\renderer\form\InputRichText::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputSignature
     */
    public function InputSignature(): \warm\admin\renderer\form\InputSignature
    {
        return \warm\admin\renderer\form\InputSignature::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputSubForm
     */
    public function InputSubForm(): \warm\admin\renderer\form\InputSubForm
    {
        return \warm\admin\renderer\form\InputSubForm::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputTable
     */
    public function InputTable(): \warm\admin\renderer\form\InputTable
    {
        return \warm\admin\renderer\form\InputTable::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputTag
     */
    public function InputTag(): \warm\admin\renderer\form\InputTag
    {
        return \warm\admin\renderer\form\InputTag::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputText
     */
    public function InputText(): \warm\admin\renderer\form\InputText
    {
        return \warm\admin\renderer\form\InputText::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputTime
     */
    public function InputTime(): \warm\admin\renderer\form\InputTime
    {
        return \warm\admin\renderer\form\InputTime::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputTimeRange
     */
    public function InputTimeRange(): \warm\admin\renderer\form\InputTimeRange
    {
        return \warm\admin\renderer\form\InputTimeRange::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputTree
     */
    public function InputTree(): \warm\admin\renderer\form\InputTree
    {
        return \warm\admin\renderer\form\InputTree::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputVerificationCode
     */
    public function InputVerificationCode(): \warm\admin\renderer\form\InputVerificationCode
    {
        return \warm\admin\renderer\form\InputVerificationCode::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputYear
     */
    public function InputYear(): \warm\admin\renderer\form\InputYear
    {
        return \warm\admin\renderer\form\InputYear::make();
    }

    /**
     * @return \warm\admin\renderer\form\InputYearRange
     */
    public function InputYearRange(): \warm\admin\renderer\form\InputYearRange
    {
        return \warm\admin\renderer\form\InputYearRange::make();
    }

    /**
     * @return \warm\admin\renderer\Json
     */
    public function Json(): \warm\admin\renderer\Json
    {
        return \warm\admin\renderer\Json::make();
    }

    /**
     * @return \warm\admin\renderer\form\JsonSchema
     */
    public function JsonSchema(): \warm\admin\renderer\form\JsonSchema
    {
        return \warm\admin\renderer\form\JsonSchema::make();
    }

    /**
     * @return \warm\admin\renderer\form\JsonSchemaEditor
     */
    public function JsonSchemaEditor(): \warm\admin\renderer\form\JsonSchemaEditor
    {
        return \warm\admin\renderer\form\JsonSchemaEditor::make();
    }

    /**
     * @return \warm\admin\renderer\Link
     */
    public function Link(): \warm\admin\renderer\Link
    {
        return \warm\admin\renderer\Link::make();
    }

    /**
     * @return \warm\admin\renderer\expand\LinkAction
     */
    public function LinkAction(): \warm\admin\renderer\expand\LinkAction
    {
        return \warm\admin\renderer\expand\LinkAction::make();
    }

    /**
     * @return \warm\admin\renderer\List
     */
    public function List(): \warm\admin\renderer\List
    {
        return \warm\admin\renderer\List::make();
    }

    /**
     * @return \warm\admin\renderer\form\ListSelect
     */
    public function ListSelect(): \warm\admin\renderer\form\ListSelect
    {
        return \warm\admin\renderer\form\ListSelect::make();
    }

    /**
     * @return \warm\admin\renderer\form\LocationPicker
     */
    public function LocationPicker(): \warm\admin\renderer\form\LocationPicker
    {
        return \warm\admin\renderer\form\LocationPicker::make();
    }

    /**
     * @return \warm\admin\renderer\Log
     */
    public function Log(): \warm\admin\renderer\Log
    {
        return \warm\admin\renderer\Log::make();
    }

    /**
     * @return \warm\admin\renderer\Mapping
     */
    public function Mapping(): \warm\admin\renderer\Mapping
    {
        return \warm\admin\renderer\Mapping::make();
    }

    /**
     * @return \warm\admin\renderer\Markdown
     */
    public function Markdown(): \warm\admin\renderer\Markdown
    {
        return \warm\admin\renderer\Markdown::make();
    }

    /**
     * @return \warm\admin\renderer\form\MatrixCheckboxes
     */
    public function MatrixCheckboxes(): \warm\admin\renderer\form\MatrixCheckboxes
    {
        return \warm\admin\renderer\form\MatrixCheckboxes::make();
    }

    /**
     * @return \warm\admin\renderer\Nav
     */
    public function Nav(): \warm\admin\renderer\Nav
    {
        return \warm\admin\renderer\Nav::make();
    }

    /**
     * @return \warm\admin\renderer\form\Nestedselect
     */
    public function Nestedselect(): \warm\admin\renderer\form\Nestedselect
    {
        return \warm\admin\renderer\form\Nestedselect::make();
    }

    /**
     * @return \warm\admin\renderer\Number
     */
    public function Number(): \warm\admin\renderer\Number
    {
        return \warm\admin\renderer\Number::make();
    }

    /**
     * @return \warm\admin\renderer\OfficeViewer
     */
    public function OfficeViewer(): \warm\admin\renderer\OfficeViewer
    {
        return \warm\admin\renderer\OfficeViewer::make();
    }

    /**
     * @return \warm\admin\renderer\OfficeViewerExcel
     */
    public function OfficeViewerExcel(): \warm\admin\renderer\OfficeViewerExcel
    {
        return \warm\admin\renderer\OfficeViewerExcel::make();
    }

    /**
     * @return \warm\admin\renderer\form\Options
     */
    public function Options(): \warm\admin\renderer\form\Options
    {
        return \warm\admin\renderer\form\Options::make();
    }

    /**
     * @return \warm\admin\renderer\Page
     */
    public function Page(): \warm\admin\renderer\Page
    {
        return \warm\admin\renderer\Page::make();
    }

    /**
     * @return \warm\admin\renderer\Pagination
     */
    public function Pagination(): \warm\admin\renderer\Pagination
    {
        return \warm\admin\renderer\Pagination::make();
    }

    /**
     * @return \warm\admin\renderer\PaginationWrapper
     */
    public function PaginationWrapper(): \warm\admin\renderer\PaginationWrapper
    {
        return \warm\admin\renderer\PaginationWrapper::make();
    }

    /**
     * @return \warm\admin\renderer\Panel
     */
    public function Panel(): \warm\admin\renderer\Panel
    {
        return \warm\admin\renderer\Panel::make();
    }

    /**
     * @return \warm\admin\renderer\PdfViewer
     */
    public function PdfViewer(): \warm\admin\renderer\PdfViewer
    {
        return \warm\admin\renderer\PdfViewer::make();
    }

    /**
     * @return \warm\admin\renderer\form\Picker
     */
    public function Picker(): \warm\admin\renderer\form\Picker
    {
        return \warm\admin\renderer\form\Picker::make();
    }

    /**
     * @return \warm\admin\renderer\Popover
     */
    public function Popover(): \warm\admin\renderer\Popover
    {
        return \warm\admin\renderer\Popover::make();
    }

    /**
     * @return \warm\admin\renderer\Portlet
     */
    public function Portlet(): \warm\admin\renderer\Portlet
    {
        return \warm\admin\renderer\Portlet::make();
    }

    /**
     * @return \warm\admin\renderer\Progress
     */
    public function Progress(): \warm\admin\renderer\Progress
    {
        return \warm\admin\renderer\Progress::make();
    }

    /**
     * @return \warm\admin\renderer\Property
     */
    public function Property(): \warm\admin\renderer\Property
    {
        return \warm\admin\renderer\Property::make();
    }

    /**
     * @return \warm\admin\renderer\Qrcode
     */
    public function Qrcode(): \warm\admin\renderer\Qrcode
    {
        return \warm\admin\renderer\Qrcode::make();
    }

    /**
     * @return \warm\admin\renderer\form\Radio
     */
    public function Radio(): \warm\admin\renderer\form\Radio
    {
        return \warm\admin\renderer\form\Radio::make();
    }

    /**
     * @return \warm\admin\renderer\Radios
     */
    public function Radios(): \warm\admin\renderer\Radios
    {
        return \warm\admin\renderer\Radios::make();
    }

    /**
     * @return \warm\admin\renderer\Remark
     */
    public function Remark(): \warm\admin\renderer\Remark
    {
        return \warm\admin\renderer\Remark::make();
    }

    /**
     * @return \warm\admin\renderer\SearchBox
     */
    public function SearchBox(): \warm\admin\renderer\SearchBox
    {
        return \warm\admin\renderer\SearchBox::make();
    }

    /**
     * @return \warm\admin\renderer\form\Select
     */
    public function Select(): \warm\admin\renderer\form\Select
    {
        return \warm\admin\renderer\form\Select::make();
    }

    /**
     * @return \warm\admin\renderer\Service
     */
    public function Service(): \warm\admin\renderer\Service
    {
        return \warm\admin\renderer\Service::make();
    }

    /**
     * @return \warm\admin\renderer\Shape
     */
    public function Shape(): \warm\admin\renderer\Shape
    {
        return \warm\admin\renderer\Shape::make();
    }

    /**
     * @return \warm\admin\renderer\Slider
     */
    public function Slider(): \warm\admin\renderer\Slider
    {
        return \warm\admin\renderer\Slider::make();
    }

    /**
     * @return \warm\admin\renderer\Sparkline
     */
    public function Sparkline(): \warm\admin\renderer\Sparkline
    {
        return \warm\admin\renderer\Sparkline::make();
    }

    /**
     * @return \warm\admin\renderer\Spinner
     */
    public function Spinner(): \warm\admin\renderer\Spinner
    {
        return \warm\admin\renderer\Spinner::make();
    }

    /**
     * @return \warm\admin\renderer\form\StaticClass
     */
    public function Static(): \warm\admin\renderer\form\StaticClass
    {
        return \warm\admin\renderer\form\StaticClass::make();
    }

    /**
     * @return \warm\admin\renderer\Status
     */
    public function Status(): \warm\admin\renderer\Status
    {
        return \warm\admin\renderer\Status::make();
    }

    /**
     * @return \warm\admin\renderer\Steps
     */
    public function Steps(): \warm\admin\renderer\Steps
    {
        return \warm\admin\renderer\Steps::make();
    }

    /**
     * @return \warm\admin\renderer\form\SwitchClass
     */
    public function Switch(): \warm\admin\renderer\form\SwitchClass
    {
        return \warm\admin\renderer\form\SwitchClass::make();
    }

    /**
     * @return \warm\admin\renderer\SwitchContainer
     */
    public function SwitchContainer(): \warm\admin\renderer\SwitchContainer
    {
        return \warm\admin\renderer\SwitchContainer::make();
    }

    /**
     * @return \warm\admin\renderer\Table
     */
    public function Table(): \warm\admin\renderer\Table
    {
        return \warm\admin\renderer\Table::make();
    }

    /**
     * @return \warm\admin\renderer\Table2
     */
    public function Table2(): \warm\admin\renderer\Table2
    {
        return \warm\admin\renderer\Table2::make();
    }

    /**
     * @return \warm\admin\renderer\TableView
     */
    public function TableView(): \warm\admin\renderer\TableView
    {
        return \warm\admin\renderer\TableView::make();
    }

    /**
     * @return \warm\admin\renderer\Tabs
     */
    public function Tabs(): \warm\admin\renderer\Tabs
    {
        return \warm\admin\renderer\Tabs::make();
    }

    /**
     * @return \warm\admin\renderer\form\TabsTransfer
     */
    public function TabsTransfer(): \warm\admin\renderer\form\TabsTransfer
    {
        return \warm\admin\renderer\form\TabsTransfer::make();
    }

    /**
     * @return \warm\admin\renderer\form\TabsTransferPicker
     */
    public function TabsTransferPicker(): \warm\admin\renderer\form\TabsTransferPicker
    {
        return \warm\admin\renderer\form\TabsTransferPicker::make();
    }

    /**
     * @return \warm\admin\renderer\Tag
     */
    public function Tag(): \warm\admin\renderer\Tag
    {
        return \warm\admin\renderer\Tag::make();
    }

    /**
     * @return \warm\admin\renderer\Tasks
     */
    public function Tasks(): \warm\admin\renderer\Tasks
    {
        return \warm\admin\renderer\Tasks::make();
    }

    /**
     * @return \warm\admin\renderer\form\Textarea
     */
    public function Textarea(): \warm\admin\renderer\form\Textarea
    {
        return \warm\admin\renderer\form\Textarea::make();
    }

    /**
     * @return \warm\admin\renderer\Timeline
     */
    public function Timeline(): \warm\admin\renderer\Timeline
    {
        return \warm\admin\renderer\Timeline::make();
    }

    /**
     * @return \warm\admin\renderer\Toast
     */
    public function Toast(): \warm\admin\renderer\Toast
    {
        return \warm\admin\renderer\Toast::make();
    }

    /**
     * @return \warm\admin\renderer\TooltipWrapper
     */
    public function TooltipWrapper(): \warm\admin\renderer\TooltipWrapper
    {
        return \warm\admin\renderer\TooltipWrapper::make();
    }

    /**
     * @return \warm\admin\renderer\Tpl
     */
    public function Tpl(): \warm\admin\renderer\Tpl
    {
        return \warm\admin\renderer\Tpl::make();
    }

    /**
     * @return \warm\admin\renderer\form\Transfer
     */
    public function Transfer(): \warm\admin\renderer\form\Transfer
    {
        return \warm\admin\renderer\form\Transfer::make();
    }

    /**
     * @return \warm\admin\renderer\form\TransferPicker
     */
    public function TransferPicker(): \warm\admin\renderer\form\TransferPicker
    {
        return \warm\admin\renderer\form\TransferPicker::make();
    }

    /**
     * @return \warm\admin\renderer\form\Treeselect
     */
    public function Treeselect(): \warm\admin\renderer\form\Treeselect
    {
        return \warm\admin\renderer\form\Treeselect::make();
    }

    /**
     * @return \warm\admin\renderer\expand\UrlAction
     */
    public function UrlAction(): \warm\admin\renderer\expand\UrlAction
    {
        return \warm\admin\renderer\expand\UrlAction::make();
    }

    /**
     * @return \warm\admin\renderer\form\Uuid
     */
    public function Uuid(): \warm\admin\renderer\form\Uuid
    {
        return \warm\admin\renderer\form\Uuid::make();
    }

    /**
     * @return \warm\admin\renderer\Video
     */
    public function Video(): \warm\admin\renderer\Video
    {
        return \warm\admin\renderer\Video::make();
    }

    /**
     * @return \warm\admin\renderer\WebComponent
     */
    public function WebComponent(): \warm\admin\renderer\WebComponent
    {
        return \warm\admin\renderer\WebComponent::make();
    }

    /**
     * @return \warm\admin\renderer\Wizard
     */
    public function Wizard(): \warm\admin\renderer\Wizard
    {
        return \warm\admin\renderer\Wizard::make();
    }

    /**
     * @return \warm\admin\renderer\Wrapper
     */
    public function Wrapper(): \warm\admin\renderer\Wrapper
    {
        return \warm\admin\renderer\Wrapper::make();
    }
}