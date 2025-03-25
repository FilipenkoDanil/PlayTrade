export const checkModerRole = () => {
    return window.Laravel?.jsPermissions?.roles?.includes('moder');
};
