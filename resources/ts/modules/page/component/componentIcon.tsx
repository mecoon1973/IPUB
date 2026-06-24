import React from "react";

interface ComponentIconProps extends React.ImgHTMLAttributes<HTMLImageElement> {
    nameIcon: string;
    folderIcon?: string;
    typeIcon?: "svg" | "png" | "jpg" | "jpeg" | "gif" | "webp";
}

export const ComponentIcon = React.memo((props: ComponentIconProps) => {
    const { nameIcon, folderIcon = "svg", typeIcon = "svg", ...rest } = props;
    return (
        <img src={`/${folderIcon}/${nameIcon}.${typeIcon}`} alt={nameIcon} {...rest} />
    );
});
ComponentIcon.displayName = "ComponentIcon";
