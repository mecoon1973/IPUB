import React from "react";
import { Button, Flex, Typography } from "antd";
import { SaveOutlined, ReloadOutlined } from '@ant-design/icons';

interface TitleStoreProps {
    title: string;
    callbackSubmit: () => void;
    disabledSubmit?: boolean;
}


export const ComponentTitleStore = React.memo((props: TitleStoreProps) => {
    const { title, callbackSubmit, disabledSubmit } = props;
    return (
        <>
            <Typography.Title level={3} className="my-2 border-bottom pb-1">
                {title}
            </Typography.Title>
            <Flex gap={8} className="justify-content-start mt-3 mb-2 border-bottom pb-1">
                <Button type="text" icon={<SaveOutlined />} className="px-1" onClick={callbackSubmit} disabled={disabledSubmit ?? false} />
                <Button type="text" icon={<ReloadOutlined />} className="px-1" onClick={() => window.history.back()} disabled={disabledSubmit ?? false} />
            </Flex>
        </>
    );
});

ComponentTitleStore.displayName = "ComponentTitleStore";
