<?php

/**
 * Helper Table
 * @$headers => Array con los campos a mostrar, estos deben coincidir con la consulta 
 * @$query => Array de consulta sql
 * @$atributes => opcional, agreagas los atributos de la tabla
 */

/**
 * @header => los valores que acepta son:
 * 'columna' = nombre de la columna en la consulta.
 * 'title' = Titluo que se desea imprimir en la cabezera de la tabla.
 * ejemp:
 * 'columna' => array('title' => 'nombre de la columna en tabla')
 * 'id' => array ('title' => "registro No.")
 *
 * 'url'(opcional) = url que se desea agregar a determinado campo.
 *
 * attr(opcional) = atributos del link, colocas class, rel etc.
 * 
 *ejemp:
 *'id' => array ('title' => "registro No.", 'url' => 'registros/ver/', 'attr' => "class='edit' rel='group-1'").
 * 
 */

class Table{

    public static function simple($headers, $query = NULL, $atributes = NULL, $noData = NULL){
        $i = 1;
        ?>
        <style type="text/css">
            .moneda{text-align: right; padding: 0 3px 0 0;}
        </style>
        <?php if($noData == NULL): ?>
            <?php echo Tag::js('jquery.dataTables')?>
            <?php echo Tag::js('TableTools')?>
            <?php echo Tag::js('ZeroClipboard')?>
            <?php echo Tag::js('reportes/alertas.tablas')?>
        <?php else: ?>
            <?php echo Tag::js('jquery.dataTables')?>
            <?php echo Tag::js('TableTools')?>
            <?php echo Tag::js('reportes/alertas.tablas')?>
        <?php endif ?>

        <table <?php print $atributes ?>>
            <thead>
                <tr>
                    <?php foreach($headers as $key => $value): ?>
                        <th><?php print $value['title'] ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($query as $query): ?>

                    <tr class="trTable <?php print $i++ ?>">
                        <?php foreach($headers as $key => $value): ?> 
                            <?php if(!isset($value['url'])): ?>              
                                <td><span><?php print $query->$key ?></span></td>
                            <?php else: ?>
                                <td class="tdTable"><?php print html::link($value['url'].$query->id, $query->$key, $value['attr']) ?></td>
                            <?php endif ?>
                        <?php endforeach ?>
                    </tr>
                <?php endforeach ?>         
            </tbody>
        </table>
        <?php
    }

    public static function modal($headers, $query = NULL, $atributes = NULL)
    {
        $i = 0;
        ?>
        <style type="text/css">
            .moneda{text-align: right; padding: 0 3px 0 0;}
        </style>

        <script>
            $(document).ready(function() {
            // Support for AJAX loaded modal window.
            // Focuses on first input textbox after it loads the window.
            $('[data-toggle="modal"]').click(function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                if (url.indexOf('#') == 0) {
                    $(url).modal('open');
                    } else {
                        $.get(url, function(data) {
                            $('<div class="modal hide fade">' + data + '</div>').modal();
                        }).success(function() { $('input:text:visible:first').focus(); });
                    }
                });
            });
        </script>


            <?php echo Tag::js('jquery.dataTables')?>
            <?php echo Tag::js('TableTools')?>

        <table <?php print $atributes ?>>
            <thead>
                <tr>
                    <?php foreach($headers as $key => $value): ?>
                        <th><?php print $value['title'] ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($query as $query): ?>

                    <tr class="trTable <?php print $i++ ?>">
                        <?php foreach($headers as $key => $value): ?>
                            <?php if(!isset($value['url'])): ?>
                                <td><span><?php print $query->$key ?></span></td>
                            <?php else: ?>
                                <td class="tdTable"><?php print html::link($value['url'].$query->id, "<i class='icon-pencil'></i>".$query->$key, " data-toggle='modal' data-target='myModal-$i' role='button' class='btn' data-toggle='modal'") ?></td>
                            <?php endif ?>
                        <?php endforeach ?>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <?php
    }
}

//<a class="btn" href="/path/to/your/page.html" data-toggle='modal' data-target='#myModal'>Launch Modal</a>