import React from "react";
import { Dropdown } from "antd";
import type { MenuProps } from "antd";

export interface ComponentDropdownProps {
    id: string;
    titleDropdown: string;
    menu: MenuProps["items"];
}

/** Dropdown hành động (`menu` là `items` của Ant Design). */
const ComponentDropdownInner = React.memo((props: ComponentDropdownProps) => {
    const { id, menu, titleDropdown } = props;
    return (
        <Dropdown menu={{ items: menu ?? [] }} trigger={["click"]}>
            <button type="button" className="btn btn-link text-decoration-none p-0 text-dark border-0 bg-transparent" id={`action-${id}`}>
                <span style={{ color: "#1677ff" }}>{titleDropdown}</span>
            </button>
        </Dropdown>
    );
});
ComponentDropdownInner.displayName = "ComponentDropdownInner";
export const ComponentDropdown = ComponentDropdownInner as (props: ComponentDropdownProps) => React.ReactElement;
