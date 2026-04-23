<?php

namespace Wiredupdev\MenuManagerBundle\Menu\UriGenerator;

enum GeneratorType: string
{
    case DIRECT_LINK_TYPE = DirectLinkGenerator::class;

    case ROUTE_LINK_TYPE = RouteLinkGenerator::class;
}
