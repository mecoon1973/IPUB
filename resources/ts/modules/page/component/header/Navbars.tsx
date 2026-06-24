import React, { useMemo } from "react";
import { Menu, Typography } from "antd";
import type { MenuProps } from "antd";
import { dashboard } from "../../config";
import type { NodeDashboard } from "../../type";

type MenuItem = Required<MenuProps>["items"][number];

const buildMenuItem = (node: NodeDashboard, key: string): MenuItem | null => {
    if (node.type === "link") {
        return {
            key,
            label: (
                <a href={node.routes || "#"} style={{ textDecoration: "none", color: "inherit" }}>
                    {node.title}
                </a>
            ),
        };
    }

    if (node.type === "navbar" || node.type === "select") {
        const children = (node.children ?? [])
            .map((child, index) => buildMenuItem(child, `${key}-${index}`))
            .filter((item): item is NonNullable<typeof item> => item != null);

        if (children.length > 0) {
            return {
                key,
                label: node.title,
                children,
            };
        }

        if (node.routes) {
            return {
                key,
                label: (
                    <a href={node.routes} style={{ textDecoration: "none", color: "inherit" }}>
                        {node.title}
                    </a>
                ),
            };
        }

        return {
            key,
            label: node.title,
            disabled: true,
        };
    }

    return null;
};

export const Navbars = React.memo(function Navbars() {
    const brandTitle = dashboard?.[0]?.title ?? "Menu";

    const menuItems = useMemo<Required<MenuProps>["items"]>(() => {
        return dashboard
            .filter((_, i) => i !== 0)
            .map((node, index) => buildMenuItem(node, `menu-${index}`))
            .filter((item): item is NonNullable<typeof item> => item != null);
    }, []);

    return (
        <div className="bg-body-tertiary px-2 py-2 d-flex align-items-center gap-3 border-bottom">
            <Typography.Text strong className="mb-0">
                {brandTitle}
            </Typography.Text>
            <Menu mode="horizontal" items={menuItems} className="grow border-0 bg-transparent" />
        </div>
    );
});

