<?php

namespace Matt;

class Time
{
	const YYYY_MM_DD_HH_MM_SS = 'Y_m_d_H_i_s';

	public static function getFormattedTime()
	{
		return date(self::YYYY_MM_DD_HH_MM_SS);
	}
}