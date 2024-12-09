"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.EnvConfigGroup = void 0;
const EnvConfigGroup = (params) => {
    const { tableName, tableConfig } = params;
    // Create the parent div container
    const div = document.createElement('div');
    div.id = `${tableName}__env_config_group`;
    // If no envConfig exists, return the empty div
    if (!tableConfig.envConfig || typeof tableConfig.envConfig !== 'object') {
        return div;
    }
    // Determine input type based on tableDebug
    const inputType = tableConfig.tableDebug ? 'text' : 'hidden';
    // Loop through envConfig keys and create input elements
    Object.entries(tableConfig.envConfig).forEach(([key, value]) => {
        if (typeof value !== 'string')
            return; // Ensure value is a string to set it as input.value
        const input = document.createElement('input');
        input.type = inputType;
        input.name = key;
        input.value = value;
        input.title = key;
        input.className = 'border rounded m-1';
        div.appendChild(input);
        // console.log('EnvConfigGroup', { key, value })
    });
    return div;
};
exports.EnvConfigGroup = EnvConfigGroup;
//# sourceMappingURL=EnvConfigGroup.js.map