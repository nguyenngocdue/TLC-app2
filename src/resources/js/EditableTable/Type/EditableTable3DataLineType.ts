export interface TableDataCell {
    value: number | string
    cell_class?: string
    cell_href?: string
    cell_title?: string
    cell_div_class?: string
    cell_onclick?: string
}

export interface TableDataLine {
    [columnName: string]: [value: TableDataCell | number | string]
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
