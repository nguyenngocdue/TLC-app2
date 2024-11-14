import {
    ControlAttributeAttachment,
    ControlAttributeCheckbox,
    ControlAttributeDropdown,
    ControlAttributeNo,
    ControlAttributeNumber,
    ControlAttributePickerDatetime,
    ControlAttributeSearchableDialog,
    ControlAttributeText,
    ControlAttributeToggle,
} from './EditableTable3ControlAttributeType'

interface BaseTableColumn {
    dataIndex: string | number
    title?: string
    subTitle?: string
    width?: number
    align?: 'center' | 'right' | 'left'
    invisible?: boolean
    tooltip?: string
    footer?: 'agg_sum' | 'agg_avg' | 'agg_count' | 'agg_max' | 'agg_min' | string
    fixed?: 'left' | 'right' | 'left-no-bg' | 'right-no-bg'
    colspan?: number // when 1st header is a group of many 2nd header columns

    //this is generated on the fly by JS
    fixedLeft?: number
    fixedRight?: number

    classList?: string

    editable?: boolean

    columnIndex?: string
    // prod_discipline_id?: number
    // target_man_minutes?: number
    // target_man_power?: number
    // target_min_uom?: number
    // isExtra?: boolean
    no_print?: boolean
    required?: boolean
    cbbDataSource?: (string | null)[]
    type?: string
    properties?: {
        placeholder?: number | string
        strFn?: string
        htmlType?: string
        lineType?: string
        lineTypeTable?: string
        lineTypeRoute?: string
        table01Name?: string
        readOnly?: boolean
        saveOnChange?: boolean
        numericScale?: string | number
        control?: string
        tableName?: string
    }

    sortBy?: string
    attributes?: {
        color?: string
        colorIndex?: string
    }
    value_as_parent_id?: boolean
    cloneable?: boolean
    rendererParam?: string

    mode?: 'edit' | 'view' | 'print' | 'csv'
}

export type TableColumnNo = BaseTableColumn & {
    renderer: 'no.'
    rendererAttrs?: ControlAttributeNo
}
export type TableColumnText = BaseTableColumn & {
    renderer: 'text' | 'text4'
    rendererAttrs?: ControlAttributeText
}
export type TableColumnNumber = BaseTableColumn & {
    renderer: 'number' | 'number4'
    rendererAttrs?: ControlAttributeNumber
}
export type TableColumnDropdown = BaseTableColumn & {
    renderer: 'dropdown' | 'dropdown4'
    rendererAttrs?: ControlAttributeDropdown
}
export type TableColumnToggle = BaseTableColumn & {
    renderer: 'toggle' | 'toggle4'
    rendererAttrs?: ControlAttributeToggle
}
export type TableColumnCheckbox = BaseTableColumn & {
    renderer: 'checkbox' | 'checkbox4'
    rendererAttrs?: ControlAttributeCheckbox
}
export type TableColumnPickerDateTime = BaseTableColumn & {
    renderer: 'picker_datetime'
    rendererAttrs?: ControlAttributePickerDatetime
}
export type TableColumnAttachment = BaseTableColumn & {
    renderer: 'attachment'
    rendererAttrs?: ControlAttributeAttachment
}
export type TableColumnSearchableDialog = BaseTableColumn & {
    renderer: 'searchable_dialog'
    rendererAttrs?: ControlAttributeSearchableDialog
}

export type TableColumn =
    | TableColumnNo
    | TableColumnText
    | TableColumnNumber
    | TableColumnDropdown
    | TableColumnToggle
    | TableColumnCheckbox
    | TableColumnPickerDateTime
