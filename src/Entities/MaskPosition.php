<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string getPoint()  The part of the face relative to which the mask should be placed. One of “forehead”, “eyes”, “mouth”, or “chin”.
 * @method float  getXShift() Shift by X-axis measured in widths of the mask scaled to the face size, from left to right. For example, choosing -1.0 will place mask just to the left of the default mask position.
 * @method float  getYShift() Shift by Y-axis measured in heights of the mask scaled to the face size, from top to bottom. For example, 1.0 will place the mask just below the default mask position.
 * @method float  getScale()  Mask scaling coefficient. For example, 2.0 means double size.
 */
class MaskPosition extends Entity
{
    public const POINT_FOREHEAD = 'forehead';

    public const POINT_EYES = 'eyes';

    public const POINT_MOUTH = 'mouth';

    public const POINT_CHIN = 'chin';
}
