interface TableColumn {
    title?: string;
    renderer?: string;
    dataIndex: string | number;
    align?: "center" | "right" | "left";
    width?: number;
    fixed?: string;
    fixedLeft?: number;
    fixedRight?: number;
    colspan?: number;
    columnIndex?: string;
    prod_discipline_id?: number;
    target_man_minutes?: number;
    target_man_power?: number;
    target_min_uom?: number;
    isExtra?: boolean;
    editable?: boolean;
    invisible?: boolean;
    no_print?: boolean;
    footer?: string;
    required?: boolean;
    cbbDataSource?: (string | null)[];
    type?: string;
    classList?: string;
    properties?: {
        placeholder?: number | string;
        strFn?: string;
        htmlType?: string;
        lineType?: string;
        lineTypeTable?: string;
        lineTypeRoute?: string;
        table01Name?: string;
        readOnly?: boolean;
        saveOnChange?: boolean;
        numericScale?: string | number;
        control?: string;
        tableName?: string;
    };
    subTitle?: string;
    sortBy?: string;
    attributes?: {
        color?: string;
        colorIndex?: string;
    };
    value_as_parent_id?: boolean;
    cloneable?: boolean;
    rendererParam?: string;
}