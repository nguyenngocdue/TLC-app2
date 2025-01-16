import { TableParams } from '../Type/EditableTable3ParamType';
import { HelloWorld } from './HelloWorld';
import { MasterDuplicateDelete } from './MasterDuplicateDelete';
import { Paginator } from './Paginator';
import { PerPage } from './PerPage';
import { TotalItems } from './TotalItems';
import { TotalItems_Paginator } from './TotalItems_Paginator';
import { TotalItems_Paginator_PerPage } from './TotalItems_Paginator_PerPage';
export declare const ToolbarComponentList: {
    helloWorld: typeof HelloWorld;
    masterDuplicateDelete: typeof MasterDuplicateDelete;
    totalItems_Paginator_PerPage: typeof TotalItems_Paginator_PerPage;
    totalItems_Paginator: typeof TotalItems_Paginator;
    perPage: typeof PerPage;
    totalItems: typeof TotalItems;
    paginator: typeof Paginator;
};
export declare class ToolbarComponents {
    private params;
    constructor(params: TableParams);
    getRealClass(componentName: keyof typeof ToolbarComponentList): HelloWorld | MasterDuplicateDelete | TotalItems_Paginator_PerPage | TotalItems_Paginator | PerPage | TotalItems | Paginator;
    static hasAnyTopComponent(params: TableParams): "helloWorld" | "masterDuplicateDelete" | "totalItems_Paginator_PerPage" | "totalItems_Paginator" | "perPage" | "totalItems" | "paginator";
    static hasAnyBottomComponent(params: TableParams): "helloWorld" | "masterDuplicateDelete" | "totalItems_Paginator_PerPage" | "totalItems_Paginator" | "perPage" | "totalItems" | "paginator";
    static register(params: TableParams): void;
}
//# sourceMappingURL=ToolbarComponents.d.ts.map