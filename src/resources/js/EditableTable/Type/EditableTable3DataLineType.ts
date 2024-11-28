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

export interface DataSourceItem {
    [key: string]: string | number
}

export interface TableCellType {
    value:
        | number
        | string
        | TableValueObjectType
        | DataSourceItem
        | Array<number | string | TableValueObjectType | DataSourceItem>
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
    links: Array<{ url: string | null; label: string; active: boolean }>
    next_page_url: string | null
    path: string
    per_page: number
    prev_page_url: string | null
    to: number
    total: number
}

export interface TableRenderedValueObject {
    rendered: string
    tdClass?: string
    tdStyle?: { [key: string]: string | number }
    tdTooltip?: string

    divClass?: string
    divStyle?: { [key: string]: string | number }
    divTooltip?: string

    applyOnMouseMoveScript?: () => void
    applyPostRenderScript?: () => void
    applyOnChangeScript?: () => void
}

export interface TableRendererParams {
    // controlName: string
    controlId: string
    cellValue: TableCellType
    params: TableParams
    dataLine: TableDataLine
    column: TableColumn
    rowIndex: number
    customRenderFn?: () => string
}
