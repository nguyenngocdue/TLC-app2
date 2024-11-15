import { TableColumn } from './EditableTable3ColumnType'
import { TableParams } from './EditableTable3ParamType'

export interface TableValueObjectType {
    value: number | string
    cell_class?: string
    cell_href?: string
    cell_title?: string
    cell_div_class?: string
    cell_onclick?: string
}

export interface TableCellTypeScalar {
    value: number | string | TableValueObjectType
}
export interface TableCellType {
    value: number | string | TableValueObjectType | Array<number | string | TableValueObjectType>
}

export interface TableDataLine {
    [columnName: string]: TableCellType
}

export interface LengthAware {
    current_page: number
    data: TableDataLine[]
    first_page_url: string
    from: number
    last_page: number
    last_page_url: string
    links: string[]
    next_page_url: string | null
    path: string
    per_page: number
    prev_page_url: string | null
    to: number
    total: number
}

export interface TableRenderedValueObject {
    rendered: string
    classStr: string
    applyPostScript?: () => void
}

export interface TableRendererParams {
    controlName: string
    controlId: string
    cellValue: TableCellType
    params: TableParams
    dataLine: TableDataLine
    column: TableColumn
    rowIndex: number
}
