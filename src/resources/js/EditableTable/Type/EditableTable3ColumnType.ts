export interface TableColumn {
    dataIndex: string | number
    title?: string
    width?: number
    align?: 'center' | 'right' | 'left'
    invisible?: boolean
    tooltip?: string

    renderer?: string
    fixed?: string
    fixedLeft?: number
    fixedRight?: number
    colspan?: number
    columnIndex?: string
    prod_discipline_id?: number
    target_man_minutes?: number
    target_man_power?: number
    target_min_uom?: number
    isExtra?: boolean
    editable?: boolean
    no_print?: boolean
    footer?: string
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
    subTitle?: string
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
