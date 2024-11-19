import {
    ControlAttributeAction,
    ControlAttributeActionCheckbox,
    ControlAttributeAggCount,
    ControlAttributeAttachment,
    ControlAttributeAvatarUser,
    ControlAttributeCheckbox,
    ControlAttributeColumn,
    ControlAttributeColumnLink,
    ControlAttributeCustomFunction,
    ControlAttributeDocId,
    ControlAttributeDropdown,
    ControlAttributeHyperLink,
    ControlAttributeIdLink,
    ControlAttributeIdStatus,
    ControlAttributeIdStatusLink,
    ControlAttributeNo,
    ControlAttributeNumber,
    ControlAttributeParentLink,
    ControlAttributePickerDatetime,
    ControlAttributeQrCode,
    ControlAttributeSearchableDialog,
    ControlAttributeStatus,
    ControlAttributeText,
    ControlAttributeTextarea,
    ControlAttributeThumbnail,
    ControlAttributeThumbnails,
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
    arraySeparator?: string

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
export type TableColumnAction = BaseTableColumn & {
    renderer: 'action.'
    rendererAttrs?: ControlAttributeAction
}
export type TableColumnActionCheckbox = BaseTableColumn & {
    renderer: 'action_checkbox.'
    rendererAttrs?: ControlAttributeActionCheckbox
}
export type TableColumnQrCode = BaseTableColumn & {
    renderer: 'qr-code'
    rendererAttrs?: ControlAttributeQrCode
}
export type TableColumnDocId = BaseTableColumn & {
    renderer: 'doc-id'
    rendererAttrs?: ControlAttributeDocId
}
export type TableColumnIdLink = BaseTableColumn & {
    renderer: 'id_link'
    rendererAttrs?: ControlAttributeIdLink
}
export type TableColumnIdStatus = BaseTableColumn & {
    renderer: 'id_status'
    rendererAttrs?: ControlAttributeIdStatus
}
export type TableColumnStatus = BaseTableColumn & {
    renderer: 'status'
    rendererAttrs?: ControlAttributeStatus
}
export type TableColumnIdStatusLink = BaseTableColumn & {
    renderer: 'id_status_link'
    rendererAttrs?: ControlAttributeIdStatusLink
}
export type TableColumnParentLink = BaseTableColumn & {
    renderer: 'parent_link'
    rendererAttrs?: ControlAttributeParentLink
}
export type TableColumnColumnLink = BaseTableColumn & {
    renderer: 'column_link'
    rendererAttrs?: ControlAttributeColumnLink
}
export type TableColumnHyperLink = BaseTableColumn & {
    renderer: 'hyper-link'
    rendererAttrs?: ControlAttributeHyperLink
}
export type TableColumnColumn = BaseTableColumn & {
    renderer: 'column'
    rendererAttrs?: ControlAttributeColumn
}
export type TableColumnAggCount = BaseTableColumn & {
    renderer: 'agg_count'
    rendererAttrs?: ControlAttributeAggCount
}
export type TableColumnAvatarUser = BaseTableColumn & {
    renderer: 'avatar_user'
    rendererAttrs?: ControlAttributeAvatarUser
}
export type TableColumnThumbnail = BaseTableColumn & {
    renderer: 'thumbnail'
    rendererAttrs?: ControlAttributeThumbnail
}
export type TableColumnThumbnails = BaseTableColumn & {
    renderer: 'thumbnails'
    rendererAttrs?: ControlAttributeThumbnails
}
export type TableColumnCustomFunction = BaseTableColumn & {
    renderer: 'custom_function'
    rendererAttrs?: ControlAttributeCustomFunction
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
export type TableColumnDateTime = BaseTableColumn & {
    renderer: 'date-time'
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
    | TableColumnAction
    | TableColumnActionCheckbox
    | TableColumnText
    | TableColumnNumber
    | TableColumnDropdown
    | TableColumnToggle
    | TableColumnCheckbox
    | TableColumnPickerDateTime
    | TableColumnAttachment
    | TableColumnSearchableDialog
    | TableColumnColumn
    | TableColumnQrCode
    | TableColumnDocId
    | TableColumnIdStatus
    | TableColumnIdLink
    | TableColumnStatus
    | TableColumnIdStatusLink
    | TableColumnAggCount
    | TableColumnAvatarUser
    | TableColumnDateTime
    | TableColumnThumbnails
    | TableColumnThumbnail
    | TableColumnColumnLink
    | TableColumnHyperLink
    | TableColumnParentLink
    | TableColumnCustomFunction

export type CbbDataSourceType = { [key: string]: { [key: string]: string | number } }
