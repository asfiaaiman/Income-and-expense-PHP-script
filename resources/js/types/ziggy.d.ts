declare module 'ziggy-js' {
    interface RouteParams {
        [key: string]: unknown;
    }
    function route(name: string, params?: RouteParams): string;
    export default route;
}
