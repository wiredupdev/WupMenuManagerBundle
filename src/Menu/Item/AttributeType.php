<?php

namespace Wiredupdev\MenuManagerBundle\Menu\Item;
/**
 *  fixed types for each part of item:
 *   - Container
 *      - before content
 *      -  item container
 *          - item  before content
 *            - item before link
 *            - item link
 *            - item after link
 *          - item after content
 *      - after content
 */
enum AttributeType : string
{
    case CONTAINER_BEFORE_CONTENT = 'container_before_content';
    case CONTAINER_AFTER_CONTENT = 'container_after_content';
    case ITEM_CONTAINER = 'item_container';
    case ITEM_LINK = 'item_link';
    case ITEM_CONTAINER_BEFORE = 'item_before_content';
    case ITEM_CONTAINER_AFTER = 'item_after_content';
    case ITEM_CONTAINER_LINK_BEFORE = 'link_before_content';
    case ITEM_CONTAINER_LINK_AFTER = 'link_after_content';

}
