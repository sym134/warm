<?php

namespace warm\admin\renderer;

class Amis
{
    public static function make()
    {
        return new self();
    }

    public function Action()
    {
        return Action::make();
    }

    public function AjaxAction()
    {
        return AjaxAction::make();
    }

    public function Alert()
    {
        return Alert::make();
    }

    public function AnchorNav()
    {
        return AnchorNav::make();
    }

    public function AnchorNavSection()
    {
        return AnchorNavSection::make();
    }

    public function ArrayControl($name = '', $label = '')
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

    public function Audio()
    {
        return Audio::make();
    }

    public function AutoFillHeight()
    {
        return AutoFillHeight::make();
    }

    public function AutoGenerateFilter()
    {
        return AutoGenerateFilter::make();
    }

    public function Avatar()
    {
        return Avatar::make();
    }

    public function Badge()
    {
        return Badge::make();
    }

    public function Barcode()
    {
        return Barcode::make();
    }

    public function BaseApi()
    {
        return BaseApi::make();
    }

    public function BaseRenderer()
    {
        return BaseRenderer::make();
    }

    public function Breadcrumb()
    {
        return Breadcrumb::make();
    }

    public function Button()
    {
        return Button::make();
    }

    public function ButtonGroup()
    {
        return ButtonGroup::make();
    }

    public function ButtonGroupControl($name = '', $label = '')
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

    public function ButtonToolbar()
    {
        return ButtonToolbar::make();
    }

    public function CRUD2Cards()
    {
        return CRUD2Cards::make();
    }

    public function CRUD2List()
    {
        return CRUD2List::make();
    }

    public function CRUD2Table()
    {
        return CRUD2::make();
    }

    public function CRUDCards()
    {
        return CRUDCards::make();
    }

    public function CRUDList()
    {
        return CRUDList::make();
    }

    public function CRUDTable()
    {
        return CRUD::make();
    }

    public function Calendar()
    {
        return Calendar::make();
    }

    public function Card()
    {
        return Card::make();
    }

    public function Card2()
    {
        return Card2::make();
    }

    public function Cards()
    {
        return Cards::make();
    }

    public function Carousel()
    {
        return Carousel::make();
    }

    public function ChainedSelectControl($name = '', $label = '')
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

    public function Chart()
    {
        return Chart::make();
    }

    public function ChartRadios()
    {
        return ChartRadios::make();
    }

    public function CheckboxControl($name = '', $label = '')
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

    public function CheckboxesControl($name = '', $label = '')
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

    public function Code()
    {
        return Code::make();
    }

    public function Collapse()
    {
        return Collapse::make();
    }

    public function CollapseGroup()
    {
        return CollapseGroup::make();
    }

    public function Color()
    {
        return Color::make();
    }

    public function Column()
    {
        return Column::make();
    }

    public function ComboCondition()
    {
        return ComboCondition::make();
    }

    public function ComboControl($name = '', $label = '')
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

    public function Component()
    {
        return Component::make();
    }

    public function ConditionBuilderControl($name = '', $label = '')
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

    public function ConditionGroupValue()
    {
        return ConditionGroupValue::make();
    }

    public function Container()
    {
        return Container::make();
    }

    public function CopyAction()
    {
        return CopyAction::make();
    }

    public function Custom()
    {
        return Custom::make();
    }

    public function Date()
    {
        return Date::make();
    }

    public function DateControl($name = '', $label = '')
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

    public function DateRange()
    {
        return DateRange::make();
    }

    public function DateRangeControl($name = '', $label = '')
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

    public function DateTimeControl($name = '', $label = '')
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

    public function Dialog()
    {
        return Dialog::make();
    }

    public function DialogAction()
    {
        return DialogAction::make();
    }

    public function DiffControl($name = '', $label = '')
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

    public function Divider()
    {
        return Divider::make();
    }

    public function Drawer()
    {
        return Drawer::make();
    }

    public function DrawerAction()
    {
        return DrawerAction::make();
    }

    public function DropdownButton()
    {
        return DropdownButton::make();
    }

    public function Each()
    {
        return Each::make();
    }

    public function EditorControl($name = '', $label = '')
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

    public function EmailAction()
    {
        return EmailAction::make();
    }

    public function Expandable()
    {
        return Expandable::make();
    }

    public function FeedbackDialog()
    {
        return FeedbackDialog::make();
    }

    public function FieldSetControl($name = '', $label = '')
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

    public function FileControl($name = '', $label = '')
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

    public function Flex()
    {
        return Flex::make();
    }

    public function Form()
    {
        return Form::make();
    }

    public function FormControl($name = '', $label = '')
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

    public function FormulaControl($name = '', $label = '')
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

    public function Grid()
    {
        return Grid::make();
    }

    public function Grid2D()
    {
        return Grid2D::make();
    }

    public function GridColumn()
    {
        return GridColumn::make();
    }

    public function GridNav()
    {
        return GridNav::make();
    }

    public function GroupControl($name = '', $label = '')
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

    public function HBox()
    {
        return HBox::make();
    }

    public function HBoxColumn()
    {
        return HBoxColumn::make();
    }

    public function HiddenControl($name = '', $label = '')
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

    public function Html()
    {
        return Html::make();
    }

    public function IFrame()
    {
        return IFrame::make();
    }

    public function Icon()
    {
        return Icon::make();
    }

    public function IconChecked()
    {
        return IconChecked::make();
    }

    public function IconItem()
    {
        return IconItem::make();
    }

    public function IconPickerControl($name = '', $label = '')
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

    public function Image($name = '', $label = '')
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

    public function ImageControl($name = '', $label = '')
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

    public function ImageToolbarAction()
    {
        return ImageToolbarAction::make();
    }

    public function Images($name = '', $label = '')
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

    public function InputCityControl($name = '', $label = '')
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

    public function InputColorControl($name = '', $label = '')
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

    public function InputDatetimeRange()
    {
        return InputDatetimeRange::make();
    }

    public function InputExcel()
    {
        return InputExcel::make();
    }

    public function InputGroupControl($name = '', $label = '')
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

    public function InputKV()
    {
        return InputKV::make();
    }

    public function InputKVS()
    {
        return InputKVS::make();
    }

    public function InputSignature($name = '', $label = '')
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

    public function InputTimeRange()
    {
        return InputTimeRange::make();
    }

    public function InputYearRange()
    {
        return InputYearRange::make();
    }

    public function InputPasswordControl($name = '', $label = '')
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

    public function JSONSchemaEditorControl($name = '', $label = '')
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

    public function Json()
    {
        return Json::make();
    }

    public function Link()
    {
        return Link::make();
    }

    public function LinkAction()
    {
        return LinkAction::make();
    }

    public function ListBodyField($name = '', $label = '')
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

    public function ListControl($name = '', $label = '')
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

    public function ListItem()
    {
        return ListItem::make();
    }

    public function ListRenderer()
    {
        return ListRenderer::make();
    }

    public function ListenerAction()
    {
        return ListenerAction::make();
    }

    public function LocationControl($name = '', $label = '')
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

    public function Log()
    {
        return Log::make();
    }

    public function Mapping($name = '', $label = '')
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

    public function Markdown()
    {
        return Markdown::make();
    }

    public function MatrixControl($name = '', $label = '')
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

    public function MonthControl($name = '', $label = '')
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

    public function MonthRangeControl($name = '', $label = '')
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

    public function MultilineText()
    {
        return MultilineText::make();
    }

    public function Nav()
    {
        return Nav::make();
    }

    public function NavItem()
    {
        return NavItem::make();
    }

    public function NavOverflow()
    {
        return NavOverflow::make();
    }

    public function NestedSelectControl($name = '', $label = '')
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

    public function NumberControl($name = '', $label = '')
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

    public function Operation()
    {
        return Operation::make();
    }

    public function Option()
    {
        return Option::make();
    }

    public function Options()
    {
        return Options::make();
    }

    public function OtherAction()
    {
        return OtherAction::make();
    }

    public function Page()
    {
        return Page::make();
    }

    public function Pagination()
    {
        return Pagination::make();
    }

    public function PaginationWrapper()
    {
        return PaginationWrapper::make();
    }

    public function Panel()
    {
        return Panel::make();
    }

    public function Password()
    {
        return Password::make();
    }

    public function PickerControl($name = '', $label = '')
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

    public function Plain()
    {
        return Plain::make();
    }

    public function Portlet()
    {
        return Portlet::make();
    }

    public function PortletTab()
    {
        return PortletTab::make();
    }

    public function Progress()
    {
        return Progress::make();
    }

    public function Property()
    {
        return Property::make();
    }

    public function QRCode()
    {
        return QRCode::make();
    }

    public function QRCodeImageSettings()
    {
        return QRCodeImageSettings::make();
    }

    public function QuarterControl($name = '', $label = '')
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

    public function QuarterRangeControl($name = '', $label = '')
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

    public function RadioControl($name = '', $label = '')
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

    public function RadiosControl($name = '', $label = '')
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

    public function RangeControl($name = '', $label = '')
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

    public function RatingControl($name = '', $label = '')
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

    public function ReloadAction()
    {
        return ReloadAction::make();
    }

    public function Remark()
    {
        return Remark::make();
    }

    public function RepeatControl($name = '', $label = '')
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

    public function RichTextControl($name = '', $label = '')
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

    public function Root()
    {
        return Root::make();
    }

    public function RowSelection()
    {
        return RowSelection::make();
    }

    public function RowSelectionOptions()
    {
        return RowSelectionOptions::make();
    }

    public function SchemaApi()
    {
        return SchemaApi::make();
    }

    public function SchemaCopyable()
    {
        return SchemaCopyable::make();
    }

    public function SchemaMessage()
    {
        return SchemaMessage::make();
    }

    public function SchemaPopOver()
    {
        return SchemaPopOver::make();
    }

    public function SearchBox()
    {
        return SearchBox::make();
    }

    public function SelectControl($name = '', $label = '')
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

    public function Service()
    {
        return Service::make();
    }

    public function SparkLine()
    {
        return SparkLine::make();
    }

    public function Spinner()
    {
        return Spinner::make();
    }

    public function State()
    {
        return State::make();
    }

    public function StaticExactControl($name = '', $label = '')
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

    public function Status($name = '', $label = '')
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

    public function Step()
    {
        return Step::make();
    }

    public function Steps()
    {
        return Steps::make();
    }

    public function SubFormControl($name = '', $label = '')
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

    public function SvgIcon()
    {
        return CustomSvgIcon::make();
    }

    public function SwitchContainer()
    {
        return SwitchContainer::make();
    }

    public function SwitchControl($name = '', $label = '')
    {
        $instance = SwitchControl::make();

        if ($name !== '') {
            $instance->name($name);
        }

        if ($label !== '') {
            $instance->label($label);
        }

        return $instance;
    }

    public function Tab()
    {
        return Tab::make();
    }

    public function Table()
    {
        return Table::make();
    }

    public function TableColumn($name = '', $label = '')
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

    public function TableControl($name = '', $label = '')
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

    public function TableSchema2()
    {
        return Table2::make();
    }

    public function TableView()
    {
        return TableView::make();
    }

    public function Tabs()
    {
        return Tabs::make();
    }

    public function TabsTransferControl($name = '', $label = '')
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

    public function TabsTransferPickerControl($name = '', $label = '')
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

    public function Tag()
    {
        return Tag::make();
    }

    public function TagControl($name = '', $label = '')
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

    public function Tasks()
    {
        return Tasks::make();
    }

    public function TextControl($name = '', $label = '')
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

    public function TextareaControl($name = '', $label = '')
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

    public function TimeControl($name = '', $label = '')
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

    public function Timeline()
    {
        return Timeline::make();
    }

    public function TimelineItem()
    {
        return TimelineItem::make();
    }

    public function Toast()
    {
        return Toast::make();
    }

    public function ToastAction()
    {
        return ToastAction::make();
    }

    public function TooltipWrapper()
    {
        return TooltipWrapper::make();
    }

    public function Tpl()
    {
        return Tpl::make();
    }

    public function TransferControl($name = '', $label = '')
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

    public function TransferPickerControl($name = '', $label = '')
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

    public function TreeControl($name = '', $label = '')
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

    public function TreeSelectControl($name = '', $label = '')
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

    public function UUIDControl($name = '', $label = '')
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

    public function UrlAction()
    {
        return UrlAction::make();
    }

    public function UserSelectControl($name = '', $label = '')
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

    public function VBox()
    {
        return VBox::make();
    }

    public function VanillaAction()
    {
        return Button::make();
    }

    public function Video()
    {
        return Video::make();
    }

    public function WangEditor($name = '', $label = '')
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

    public function Watermark()
    {
        return CustomWatermark::make();
    }

    public function WebComponent()
    {
        return WebComponent::make();
    }

    public function Wizard()
    {
        return Wizard::make();
    }

    public function WizardStep($name = '', $label = '')
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

    public function Words()
    {
        return Words::make();
    }

    public function Wrapper()
    {
        return Wrapper::make();
    }

    public function YearControl($name = '', $label = '')
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
