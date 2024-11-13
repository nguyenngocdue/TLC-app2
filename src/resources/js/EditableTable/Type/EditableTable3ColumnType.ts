export interface TableColumn {
    dataIndex: string | number
    title?: string
    subTitle?: string
    width?: number
    align?: 'center' | 'right' | 'left'
    invisible?: boolean
    tooltip?: string
    footer?: 'agg_sum' | 'agg_avg' | 'agg_count' | 'agg_max' | 'agg_min' | string
    colspan?: number // when 1st header is a group of many 2nd header columns
    fixed?: 'left' | 'right' | 'left-no-bg' | 'right-no-bg'

    //this is generated on the fly by JS
    fixedLeft?: number
    fixedRight?: number

    renderer?: string
    columnIndex?: string
    prod_discipline_id?: number
    target_man_minutes?: number
    target_man_power?: number
    target_min_uom?: number
    isExtra?: boolean
    editable?: boolean
    no_print?: boolean
    required?: boolean
    cbbDataSource?: (string | null)[]
    type?: string
    classList?: string
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

    mode?: 'edit' | 'view' | 'print'
}
