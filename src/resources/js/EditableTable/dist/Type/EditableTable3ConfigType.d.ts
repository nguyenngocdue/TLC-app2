import { ToolbarComponentList } from '../ToolbarComponents/ToolbarComponents';
export interface TableConfig {
    width?: number;
    maxH?: number | null;
    borderColor?: string;
    showNo?: boolean;
    tableDebug?: boolean;
    tableTrueWidth?: boolean;
    tableHeader?: string;
    tableFooter?: string;
    topLeftControl?: keyof typeof ToolbarComponentList;
    topCenterControl?: keyof typeof ToolbarComponentList;
    topRightControl?: keyof typeof ToolbarComponentList;
    bottomLeftControl?: keyof typeof ToolbarComponentList;
    bottomCenterControl?: keyof typeof ToolbarComponentList;
    bottomRightControl?: keyof typeof ToolbarComponentList;
    rotate45Width?: number;
    rotate45Height?: number;
    classList?: {
        text: string;
        textarea: string;
        dropdown: string;
        toggle: string;
        toggle_checkbox: string;
        button: string;
        dropdown_fake: string;
    };
    animationDelay?: number;
    rowHeight?: number;
    orderable?: boolean;
    duplicatable?: boolean;
    deletable?: boolean;
    uploadServiceEndpoint?: string;
    showButton?: {
        AddAnItem?: boolean;
        AddFromList?: boolean;
        CloneFromTemplate?: boolean;
        Recalculate?: boolean;
    };
    entityLineType: string;
    envConfig?: {
        entityParentType?: string;
        entityParentId?: string;
        currentUserId?: string;
        entityProjectId?: string;
        entitySubProjectId?: string;
        tableNames?: {
            [table01Name: string]: string;
        };
        tableFnNames?: {
            [table01Name: string]: string;
        };
    };
}
//# sourceMappingURL=EditableTable3ConfigType.d.ts.map