<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Basic\BracesPositionFixer;
use PhpCsFixer\Fixer\CastNotation\CastSpacesFixer;
use PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer;
use PhpCsFixer\Fixer\ControlStructure\TrailingCommaInMultilineFixer;
use PhpCsFixer\Fixer\FunctionNotation\FunctionDeclarationFixer;
use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Operator\NotOperatorWithSuccessorSpaceFixer;
use PhpCsFixer\Fixer\Operator\UnaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Phpdoc\GeneralPhpdocAnnotationRemoveFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocLineSpanFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\ArrayListItemNewlineFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\ArrayOpenerAndCloserNewlineFixer;
use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\CodingStandard\Fixer\Spacing\MethodChainingNewlineFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
    ])
    ->withPreparedSets(
        psr12: true,
        common: true,
        symplify: true,
        strict: true,
        cleanCode: true,
    )
    ->withPhpCsFixerSets(
        perCS20: true,
    )
    // import SetList here on purpose to avoid overridden by existing Skip Option in current config
    ->withConfiguredRule(PhpdocLineSpanFixer::class, ['const' => 'single', 'property' => 'single'])
    ->withConfiguredRule(LineLengthFixer::class, [LineLengthFixer::INLINE_SHORT_LINES => false])
    ->withConfiguredRule(
        GeneralPhpdocAnnotationRemoveFixer::class,
        ['annotations' => ['author', 'package', 'group', 'covers', 'category']],
    )
    ->withSkip([TrailingCommaInMultilineFixer::class])
    ->withConfiguredRule(
        TrailingCommaInMultilineFixer::class,
        ['elements' => ['arrays', 'parameters']],
    )
    ->withSkip([
        NotOperatorWithSuccessorSpaceFixer::class,
        CastSpacesFixer::class,
        BinaryOperatorSpacesFixer::class,
        UnaryOperatorSpacesFixer::class,
        FunctionDeclarationFixer::class,
        ClassAttributesSeparationFixer::class,
        MethodChainingNewlineFixer::class,
        ArrayOpenerAndCloserNewlineFixer::class,
        ArrayListItemNewlineFixer::class,
    ]);
